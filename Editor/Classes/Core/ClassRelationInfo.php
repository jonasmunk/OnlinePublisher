<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ClassRelationInfo {
	var $fromClass;
	var $fromProperty;
	var $toClass;
	var $toProperty;
	
	function setFromClass($fromClass) {
	    $this->fromClass = $fromClass;
	}
	
	function getFromClass() {
	    return $this->fromClass;
	}
	
	function setFromProperty($fromProperty) {
	    $this->fromProperty = $fromProperty;
	}
	
	function getFromProperty() {
	    return $this->fromProperty;
	}
	
	function setToClass($toClass) {
	    $this->toClass = $toClass;
	}
	
	function getToClass() {
	    return $this->toClass;
	}
	
	function setToProperty($toProperty) {
	    $this->toProperty = $toProperty;
	}
	
	function getToProperty() {
	    return $this->toProperty;
	}
	
}