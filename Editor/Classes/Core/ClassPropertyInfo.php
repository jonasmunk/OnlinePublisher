<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ClassPropertyInfo {
	var $name;
	var $type;
	var $origin;
	var $value;
	
	function getName() {
		return $this->name;
	}
	
	function setName($name) {
		$this->name = $name;
	}
	
	function getType() {
		return $this->type;
	}
	
	function setType($type) {
		$this->type = $type;
	}
	
	function getOrigin() {
		return $this->origin;
	}
	
	function setOrigin($origin) {
		$this->origin = $origin;
	}
	
	function getValue() {
		return $this->value;
	}
	
	function setValue($value) {
		$this->value = $value;
	}
}