<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Part
{
	static $schema = array();
	protected $id;
	protected $type;
	protected $dynamic;
	
	function Part($type) {
		$this->type = $type;
	}
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}

	function getType() {
	    return $this->type;
	}
	
	function save() {
		PartService::save($this);
	}
	
	function isDynamic() {
		$ctrl = PartService::getController($this->type);
		if ($ctrl) {
			return $ctrl->isDynamic($this);
		}
		return false;
	}
	
	function remove() {
		PartService::remove($this);
	}
	
	function isPersistent() {
		return $this->id!=null;
	}
	
	function load($type,$id) {
		return PartService::load($type,$id);
	}
}

?>