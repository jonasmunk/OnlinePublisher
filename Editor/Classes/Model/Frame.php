<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Frame'] = array(
	'table' => 'page',
	'properties' => array(
		'title' => array('type'=>'string'),
		'hierarchyId' => array('type'=>'int','relation'=>array('class'=>'Hierarchy','property'=>'id'))
	)
);
class Frame extends Entity {
       
	var $title;
	var $name;
	var $bottomText;
	var $hierarchyId;
	var $changed;
	var $searchEnabled;
	var $searchPageId;
	var $userStatusEnabled;
	var $loginPageId;

    function Frame() {
    }

	function isPersistent() {
		return $this->id>0;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setName($name) {
	    $this->name = $name;
	}

	function getName() {
	    return $this->name;
	}
	
	function setHierarchyId($id) {
	    $this->hierarchyId = $id;
	}

	function getHierarchyId() {
	    return $this->hierarchyId;
	}
	
	function getChanged() {
		return $this->changed;
	}
	
	function setChanged($changed) {
		$this->changed = $changed;
	}
	
	function setBottomText($bottomText) {
	    $this->bottomText = $bottomText;
	}

	function getBottomText() {
	    return $this->bottomText;
	}
	
	function setSearchEnabled($searchEnabled) {
	    $this->searchEnabled = $searchEnabled;
	}

	function getSearchEnabled() {
	    return $this->searchEnabled;
	}
	
	function setSearchPageId($searchPageId) {
	    $this->searchPageId = $searchPageId;
	}

	function getSearchPageId() {
	    return $this->searchPageId;
	}
	
	function setUserStatusEnabled($userStatusEnabled) {
	    $this->userStatusEnabled = $userStatusEnabled;
	}

	function getUserStatusEnabled() {
	    return $this->userStatusEnabled;
	}
	
	function setLoginPageId($loginPageId) {
	    $this->loginPageId = $loginPageId;
	}

	function getLoginPageId() {
	    return $this->loginPageId;
	}
		
	// Utils...

	static function load($id) {
		return FrameService::load($id);
	}

	function save() {
		return FrameService::save($this);
	}
	
	function remove() {
		return FrameService::remove($this);
	}
	
    function canRemove() {
		return FrameService::canRemove($this);
    }

	static function search() {
		return FrameService::search();
	}
}