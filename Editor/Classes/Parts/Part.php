<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Part'] = [
	'table' => 'part',
	'properties' => [
		'type' => ['type' => 'text'],
		'dynamic' => ['type' => 'boolean']
	]
];

class Part extends Entity
{
	var $type;
	var $dynamic;
	
	function Part($type) {
		$this->type = $type;
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
	
	static function get($type,$id) {
		return PartService::load($type,$id);
	}
}

?>