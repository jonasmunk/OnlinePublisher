<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Links
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class LinkService {
	
	static $types = array(
		'url' => array('da' => 'Internet adresse', 'en' => 'Internet address'),
		'page' => array('da' => 'Side', 'en' => 'Page'),
		'file' => array('da' => 'Fil', 'en' => 'File'),
		'email' => array('da' => 'E-mail-adresse', 'en' => 'E-mail address')
	);
	
	function getLinkInfo($linkId) {
		$sql = "select link.*,page.title as page_title,object.title as object_title from link left join page on link.target_id=page.id left join object on link.target_id=object.id where link.id=".Database::int($linkId);
		if ($row = Database::selectFirst($sql)) {
			return LinkService::_rowToInfo($row);
		}
		return null;
	}

	function getPageLinks($pageId) {
		$list = array();
		$sql = "select link.*,page.title as page_title,object.title as object_title from link left join page on link.target_id=page.id left join object on link.target_id=object.id where page_id=".Database::int($pageId)." order by link.source_text";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$link = LinkService::_rowToInfo($row);
			$list[] = $link;
		}
		Database::free($result);
		return $list;
	}
	
	function _rowToInfo($row) {
		$link = new LinkInfo();
		$link->setId(intval($row['id']));
		$link->setSourceType($row['source_type']);
		$link->setSourceText($row['source_text']);
		$link->setTargetType($row['target_type']);
		$link->setTargetValue($row['target_value']);
		$link->setTargetId(intval($row['target_id']));
		$link->setPartId(intval($row['part_id']));
		if ($row['target_type']=='page') {
			$link->setTargetTitle($row['page_title']);
			$link->setTargetIcon('common/page');
		} else if ($row['target_type']=='file') {
			$link->setTargetTitle($row['object_title']);
			$link->setTargetIcon('file/generic');
		} else if ($row['target_type']=='email') {
			$link->setTargetTitle($row['target_value']);
			$link->setTargetIcon('common/email');
		} else {
			$link->setTargetTitle($row['target_value']);
			$link->setTargetIcon('common/internet');
		}
		return $link;
	}
	
	function translateLinkType($type) {
		return LinkService::$types[$type]['da'];
	}
	
	function load($id) {
		$sql = "select * from link where id=".Database::int($id);
        if ($row = Database::selectFirst($sql)) {
			$link = new Link();
			$link->setId(intval($id));
			$link->setText($row['source_text']);
			$link->setAlternative($row['alternative']);
			$link->targetType=$row['target_type'];
			$link->targetValue=$row['target_value'];
			$link->targetId=intVal($row['target_id']);
			$link->partId=intVal($row['part_id']);
			$link->pageId=intVal($row['page_id']);
			return $link;
		}
		return null;
	}

	function remove($link) {
		$sql="delete from link where id=".Database::int($this->getId());
		return Database::delete($sql);
	}

	function save($link) {
		if (StringUtils::isBlank($link->getText())) {
			return;
		}
		if ($link->id) {
			$sql="update link set ".
			"part_id=".Database::int($link->partId).
			",page_id=".Database::int($link->pageId).
			",source_text=".Database::text($link->text).
			",target_type=".Database::text($link->targetType).
			",target_value=".Database::text($link->targetValue).
			",target_id=".Database::int($link->targetId).
			",target=".Database::text($link->target).
			",alternative=".Database::text($link->alternative).
			" where id=".Database::int($link->id);
			Database::update($sql);
		} else {
			$sql="insert into link (page_id,part_id,source_type,source_text,target_type,target_value,target_id,target,alternative
				) values (".
				Database::int($link->pageId).
				",".Database::int($link->partId).
				",'text',".
				Database::text($link->text).",".
				Database::text($link->targetType).",".
				Database::text($link->targetValue).",".
				Database::int($link->targetId).",".
				Database::text($link->target).",".
				Database::text($link->alternative).
			")";
			$this->id = Database::insert($sql);
		}
	}
}