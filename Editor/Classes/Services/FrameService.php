<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');

class FrameService {
	
	function save($frame) {
		$hierarchy = Hierarchy::load($frame->getHierarchyId());
		if (!$hierarchy) {
			return false;
		}
		$sql = array(
			'table' => 'frame',
			'values' => array(
				'title' => Database::text($frame->getTitle()),
				'name' => Database::text($frame->getName()),
				'bottomtext' => Database::text($frame->getBottomText()),
				'hierarchy_id' => Database::int($frame->getHierarchyId()),
				'changed' => Database::datetime(time()),
				'searchenabled' => Database::boolean($frame->getSearchEnabled()),
				'searchpage_id' => Database::int($frame->getSearchPageId()),
				'userstatusenabled' => Database::boolean($frame->getUserStatusEnabled()),
				'userstatuspage_id' => Database::int($frame->getLoginPageId())
			),
			'where' => array( 'id' => $frame->getId())
		);
		if ($frame->getId()>0) {
			Database::update($sql);
		} else {
			$frame->id = Database::insert($sql);
		}
		return true;
	}
	
	function getLinks($frame,$position) {
		$links = array();
		$sql = "select frame_link.*,page.title as page_title,object.title as object_title from frame_link left join page on page.id=`frame_link`.`target_id` left join object on object.id=`frame_link`.`target_id` where frame_link.frame_id=".Database::int($frame->getId())." and frame_link.position=".Database::text($position)." order by frame_link.`index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$link = array(
				'text' => $row['title'],
				'kind' => $row['target_type'],
			);
			if ($row['target_type']=='page') {
				$link['value'] = $row['target_id'];
				$link['info'] = $row['page_title'];
				$link['icon'] = 'common/page';
			} else if ($row['target_type']=='file') {
				$link['value'] = $row['target_id'];
				$link['info'] = $row['object_title'];
				$link['icon'] = 'monochrome/file';
			} else if ($row['target_type']=='url') {
				$link['value'] = $row['target_value'];
				$link['info'] = $row['target_value'];
				$link['icon'] = 'monochrome/globe';
			} else if ($row['target_type']=='email') {
				$link['value'] = $row['target_value'];
				$link['info'] = $row['target_value'];
				$link['icon'] = 'monochrome/email';
			}
			$links[] = $link;
		}
		return $links;
	}
	
	function replaceLinks($frame,$topLinks,$bottomLinks) {
		if (!is_object($frame)) {
			Log::debug('No frame provided');
			return;
		}
		$sql = "delete from frame_link where frame_id=".Database::int($frame->getId());
		Database::delete($sql);
		FrameService::_createLinks($frame,$topLinks,'top');
		FrameService::_createLinks($frame,$bottomLinks,'bottom');
	}
		
	function _createLinks($frame,$links,$position) {
		$index = 0;
		foreach ($links as $link) {
			$id = 0;
			$value = null;
			if ($link->kind=='page') {
				$type = 'page';
				$id = $link->value;
			} else if ($link->kind=='file') {
				$type = 'file';
				$id = $link->value;
			} else if ($link->kind=='url') {
				$type = 'url';
				$value = $link->value;
			} else if ($link->kind=='email') {
				$type = 'email';
				$value = $link->value;
			}
			if ($type) {
				$sql = array(
					'table'=>'frame_link',
					'values'=> array(
						'frame_id' => Database::int($frame->getId()),
						'position' => Database::text($position),
						'title' => Database::text($link->text),
						'target_type' => Database::text($type),
						'target_id' => Database::int($id),
						'target_value' => Database::text($value),
						'index' => Database::int($index)
					)
				);
				Database::insert($sql);
			}
			$index++;
		}
	}
	
	function load($id) {
		$sql = "select id,title,bottomtext,name,hierarchy_id,UNIX_TIMESTAMP(changed) as changed,searchenabled,searchpage_id,userstatusenabled,userstatuspage_id from frame where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			$frame = new Frame();
			$frame->setId(intval($row['id']));
			$frame->setTitle($row['title']);
			$frame->setBottomText($row['bottomtext']);
			$frame->setName($row['name']);
			$frame->setHierarchyId(intval($row['hierarchy_id']));
			$frame->setSearchEnabled($row['searchenabled'] ? true : false);
			$frame->setSearchPageId(intval($row['searchpage_id']));
			$frame->setUserStatusEnabled($row['userstatusenabled'] ? true : false);
			$frame->setLoginPageId(intval($row['userstatuspage_id']));
			$frame->changed = intval($row['changed']);
			return $frame;
		}
		return null;
	}
	
    function canRemove($frame) {
        $sql="select count(id) as num from page where frame_id=".Database::int($frame->getId());
        if ($row = Database::selectFirst($sql)) {
            return $row['num']==0;
        }
        return true;
    }
	
	function remove($frame) {
		if ($frame->getId()>0 && FrameService::canRemove($frame)) {
			$sql = "delete from frame where id=".Database::int($frame->getId());
			Database::delete($sql);
			$sql = "delete from frame_link where frame_id=".Database::int($frame->getId());
			Database::delete($sql);
			
			$sql="select * from frame_newsblock where frame_id=".Database::int($frame->getId());
			$result = Database::select($sql);
			while ($row = Database::next($result)) {
				$sql='delete from frame_newsblock_newsgroup where frame_newsblock_id='.Database::int($row['id']);
				Database::delete($sql);
			}
			Database::free($result);
			
			$sql = "delete from frame_newsblock where frame_id=".Database::int($frame->getId());
			Database::delete($sql);
			
			return true;
		}
		return false;
	}

	function search() {
		$list = array();
		$sql = "select id,title,bottomtext,name,hierarchy_id,UNIX_TIMESTAMP(changed) as changed from frame order by name";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$frame = new Frame();
			$frame->setId($row['id']);
			$frame->setTitle($row['title']);
			$frame->setName($row['name']);
			$frame->setBottomText($row['bottomtext']);
			$frame->setHierarchyId($row['hierarchy_id']);
			$frame->setChanged(intval($row['changed']));
			$list[] = $frame;
		}
		Database::free($result);
		return $list;
	}
	
	function getNewsBlocks($frame) {
		$sql = "select id,`index`,title,sortby,sortdir,maxitems,timetype,timecount,UNIX_TIMESTAMP(startdate) as startdate,UNIX_TIMESTAMP(enddate) as enddate from frame_newsblock where frame_id=".Database::int($frame->getId())." order by `index`";
		$blocks = Database::selectAll($sql);
		foreach ($blocks as &$block) {
			$sql = "select newsgroup_id from frame_newsblock_newsgroup where frame_newsblock_id=".Database::int($block['id']);
			$block['groups'] = Database::selectArray($sql);
		}
		return $blocks;
	}
	
	function replaceNewsBlocks($frame,$blocks) {
		Log::debug($blocks);
		
		if (!is_object($frame)) {
			Log::debug('No frame provided');
			return;
		}
		// Delete existing blocks
		$sql = "delete from frame_newsblock where frame_id=".Database::int($frame->getId());
		Database::delete($sql);
		// Delete unassociated news groups
		$sql = "delete from frame_newsblock_newsgroup where frame_newsblock_id not in (select id from frame_newsblock)";
		Database::delete($sql);
		
		if (!is_array($blocks)) {
			return;
		}

		for ($i=0; $i < count($blocks); $i++) {
			$block = $blocks[$i];
			if ($block->startdate) {
				//$block->startdate = DateUtils::parseRFC3339($block->startdate);
				//$block->enddate = DateUtils::parseRFC3339($block->enddate);
			}
			$sql = array(
				'table'=>'frame_newsblock',
				'values'=> array(
					'frame_id' => Database::int($frame->getId()),
					'title' => Database::text($block->title),
					'sortby' => Database::text($block->sortby),
					'sortdir' => Database::text($block->sortdir),
					'maxitems' => Database::int($block->maxitems),
					'timetype' => Database::text($block->timetype),
					'timecount' => Database::int($block->timecount),
					'startdate' => Database::datetime($block->startdate),
					'enddate' => Database::datetime($block->enddate)
				)
			);
			Database::insert($sql);
		}
	}
}
?>