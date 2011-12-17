<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class Weblog {
	
	var $id;
	var $title;
	var $pageBlueprintId;
	var $groupIds;
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setGroupIds($groups) {
	    $this->groupIds = $groups;
	}

	function getGroupIds() {
	    return $this->groupIds;
	}
	
	function setPageBlueprintId($pageBlueprintId) {
	    $this->pageBlueprintId = $pageBlueprintId;
	}

	function getPageBlueprintId() {
	    return $this->pageBlueprintId;
	}
	
	function save() {
		$sql = "update weblog set pageblueprint_id=".Database::int($this->pageBlueprintId).",title=".Database::text($this->title)." where page_id=".$this->id;
		Database::update($sql);

		$sql = "delete from weblog_webloggroup where page_id=".$this->id;
		Database::delete($sql);

		foreach ($this->groupIds as $group) {
			$sql = "insert into weblog_webloggroup (page_id,webloggroup_id) values (".Database::int($this->id).",".Database::int($group).")";
			Database::insert($sql);
		}
		PageService::markChanged($this->id);
	}
	
	function load($id) {
		$sql="select * from weblog where page_id=".Database::int($id);
		if ($row = Database::getRow($sql)) {
			$obj = new Weblog();
			$obj->setId(intval($row['page_id']));
			$obj->setTitle($row['title']);
			$obj->setPageBlueprintId(intval($row['pageblueprint_id']));
			
			$sql="select webloggroup_id as id from weblog_webloggroup where page_id=".InternalSession::getPageId();
			$groups = Database::selectIntArray($sql);
			$obj->setGroupIds($groups);
			
			return $obj;
		}
		return null;
	}
}