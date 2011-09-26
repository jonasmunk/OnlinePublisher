<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');
require_once($basePath.'Editor/Classes/Services/FrameService.php');

class Frame {
        
	var $id;
	var $title;
	var $name;
	var $bottomText;
	var $hierarchyId;
	var $changed;

    function Frame() {
    }

	function isPersistent() {
		return $this->id>0;
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
	
	
	// Utils...

	function load($id) {
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

	function search() {
		return FrameService::search();
	}
}