<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['news'] = array(
	'imageId'   => array('type'=>'int','column'=>'image_id'),
	'startdate'  => array('type'=>'datetime'),
	'enddate'  => array('type'=>'datetime')
);

class News extends Object {
	var $startdate;
	var $enddate;
	var $imageId;
	
	function News() {
		parent::Object('news');
	}
	
	function load($id) {
		return Object::get($id,'news');
	}
	
	function setStartdate($stamp) {
		$this->startdate = $stamp;
	}
	
	function getStartdate() {
		return $this->startdate;
	}
	
	function setEnddate($stamp) {
		$this->enddate = $stamp;
	}
	
	function getEnddate() {
		return $this->enddate;
	}
	
	function setImageId($id) {
		$this->imageId = $id;
	}
	
	function getImageId() {
		return $this->imageId;
	}
	
	////////////////////////////////// Groups //////////////////////////////
	
	function getGroupIds() {
		$sql="select newsgroup_id as id from newsgroup_news where news_id=".$this->id;
		return Database::getIds($sql);
	}
	
	function updateGroupIds($ids) {
		$sql="delete from newsgroup_news where news_id=".$this->id;
		Database::delete($sql);
		if (is_array($ids)) {
			foreach ($ids as $id) {
				$sql="insert into newsgroup_news (news_id, newsgroup_id) values (".$this->id.",".$id.")";
				Database::insert($sql);
			}
		}
	}
	
	function addGroupId($id) {
		$sql = "delete from newsgroup_news where news_id=".Database::int($this->id)." and newsgroup_id=".Database::int($id);
		Database::delete($sql);
		$sql = "insert into newsgroup_news (newsgroup_id,news_id) values (".Database::int($id).",".Database::int($this->id).")";
		Database::insert($sql);
	}
	
	///////////////////////////////// Search //////////////////////////
	
	function addCustomSearch($query,&$parts) {
		$custom = $query->getCustom();
		if ($custom['group']>0) {
			$parts['tables'][] = 'newsgroup_news';
			$parts['limits'][] = 'newsgroup_news.news_id=object.id';
			$parts['limits'][] = 'newsgroup_news.newsgroup_id='.$custom['group'];
		}
		if (isset($custom['startdate']) && isset($custom['enddate'])) {
			$start = $custom['startdate'];
			$end = $custom['enddate'];
			$parts['limits'][] = "((news.startdate is null and news.enddate is null) or (news.startdate>=".Database::datetime($start)." and news.startdate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.enddate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.startdate is null) or (news.startdate<=".Database::datetime($end)." and news.enddate is null))";
		}
		if (isset($custom['active'])) {
			if ($custom['active']) {
				$parts['limits'][] = '((news.startdate is null and news.enddate is null) or (news.startdate<now() and news.enddate>now()) or (news.startdate is null and news.enddate>now()) or (news.startdate<now() and news.enddate is null))';
			} else {
				$parts['limits'][] = 'not ((news.startdate is null and news.enddate is null) or (news.startdate<now() and news.enddate>now()) or (news.startdate is null and news.enddate>now()) or (news.startdate<now() and news.enddate is null))';
			}
		}
		if (isset($custom['linkType'])) {
			$parts['tables'][] = 'object_link';
			$parts['limits'][] = 'object_link.object_id=object.id';
			$parts['limits'][] = 'object_link.target_type='.Database::text($custom['linkType']);
		}
	}
	
	////////////////////////////// Persistence ////////////////////////
	
	
	function sub_publish() {
		$data = '<news xmlns="'.parent::_buildnamespace('1.0').'">';
		if (isset($this->startdate)) {
			$data.=DateUtils::buildTag('startdate',$this->startdate);
		}
		if (isset($this->enddate)) {
			$data.=DateUtils::buildTag('enddate',$this->enddate);
		}
		if ($this->imageId) {
			$data.=Object::getObjectData($this->imageId);
		}
		$data.='</news>';
		return $data;
	}
	
	function removeMore() {
		$sql="delete from newsgroup_news where news_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
		
	function getIcon() {
        return "common/news";
	}
}
?>