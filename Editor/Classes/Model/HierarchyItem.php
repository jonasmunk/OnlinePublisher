<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['HierarchyItem'] = [
	'table' => 'hierarchy_item',
	'properties' => [
		'title' => ['type'=>'string'],
		'hidden' => ['type'=>'boolean'],
		'targetType' => ['type'=>'string'],
		'parent' => ['type' => 'int'],
		'targetValue' => ['type'=>'int','relations' => [
			['class'=>'Page','property'=>'id'],
			['class'=>'File','property'=>'id']
			]
		]
	]
];
class HierarchyItem extends Entity implements Loadable {
        
	var $title;
	var $hidden;
	var $canDelete;
	var $targetType;
	var $targetValue;
    var $hierarchyId;
	var $parent;
	var $index;

    function HierarchyItem() {
    }
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
    
    function setHierarchyId($hierarchyId) {
        $this->hierarchyId = $hierarchyId;
    }
    
    function getHierarchyId() {
        return $this->hierarchyId;
    }
    
	function setParent($parent) {
	    $this->parent = $parent;
	}
	
	function getParent() {
	    return $this->parent;
	}
	
	function setIndex($index) {
	    $this->index = $index;
	}
	
	function getIndex() {
	    return $this->index;
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
	
	static function load($id) {
		$sql = "select id,title,hidden,target_type,target_value,target_id,hierarchy_id,parent,`index` from hierarchy_item where id=".Database::int($id);
		$result = Database::select($sql);
		$item = null;
		if ($row = Database::next($result)) {
			$item = new HierarchyItem();
			$item->setId(intval($row['id']));
			$item->setTitle($row['title']);
			$item->setHidden($row['hidden']==1);
			$item->setTargetType($row['target_type']);
			$item->setHierarchyId(intval($row['hierarchy_id']));
			$item->setParent(intval($row['parent']));
			$item->setIndex(intval($row['index']));
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
    
    static function loadByPageId($id) {
        return HierarchyService::getItemByPageId($id);
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
    	    ",hierarchy_id=".Database::int($this->hierarchyId).
    	    ",parent=".Database::int($this->parent).
			",`index`=".Database::int($this->index).
			" where id=".$this->id;
			Database::update($sql);
			Hierarchy::markHierarchyOfItemChanged($this->id);
		}
	}
}