<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');
require_once($basePath.'Editor/Classes/Model/Hierarchy.php');

class HierarchyItem {
        
	var $id;
	var $title;
	var $hidden;
	var $canDelete;
	var $targetType;
	var $targetValue;

    function HierarchyItem() {
    }

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

	function setHidden($hidden) {
	    $this->hidden = $hidden;
	}

	function getHidden() {
	    return $this->hidden;
	}
	
	function setCanDelete($canDelete) {
	    $this->canDelete = $canDelete;
	}

	function getCanDelete() {
	    return $this->canDelete;
	}
	
	function setTargetType($targetType) {
	    $this->targetType = $targetType;
	}

	function getTargetType() {
	    return $this->targetType;
	}
	
	function setTargetValue($targetValue) {
	    $this->targetValue = $targetValue;
	}

	function getTargetValue() {
	    return $this->targetValue;
	}
	
	function load($id) {
		$sql = "select id,title,hidden,target_type,target_value,target_id from hierarchy_item where id=".Database::int($id);
		$result = Database::select($sql);
		$item = null;
		if ($row = Database::next($result)) {
			$item = new HierarchyItem();
			$item->setId($row['id']);
			$item->setTitle($row['title']);
			$item->setHidden($row['hidden']==1);
			$item->setTargetType($row['target_type']);
			if ($row['target_type']=='page' || $row['target_type']=='pageref' || $row['target_type']=='file') {
				$item->setTargetValue($row['target_id']);
			} else {
				$item->setTargetValue($row['target_value']);
			}
			$sql="select * from hierarchy_item where parent=".Database::int($id);
			$item->canDelete = Database::isEmpty($sql);
		}
		Database::free($result);
		return $item;
	}
	
	function save() {
		if ($this->id>0) {
			$target_value = null;
			$target_id = null;
			if ($this->targetType=='page' || $this->targetType=='pageref' || $this->targetType=='file') {
				$target_id = $this->targetValue;
			} else {
				$target_value = $this->targetValue;
			}
			$sql="update hierarchy_item set".
			" title=".Database::text($this->title).
			",hidden=".Database::boolean($this->hidden).
			",target_type=".Database::text($this->targetType).
			",target_value=".Database::text($target_value).
			",target_id=".Database::int($target_id).
			" where id=".$this->id;
			Database::update($sql);
			Hierarchy::markHierarchyOfItemChanged($this->id);
		}
	}
}