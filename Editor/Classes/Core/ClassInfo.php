<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ClassInfo {
	var $name;
	var $parent;
	var $hierarchy;
	var $path;
	var $properties;
	var $relations;
	
	function getName() {
		return $this->name;
	}
	
	function setName($name) {
		$this->name = $name;
	}
	
	function getParent() {
		return $this->parent;
	}
	
	function setParent($parent) {
		$this->parent = $parent;
	}
	
	function getHierarchy() {
		return $this->hiearchy;
	}
	
	function setHierarchy($hiearchy) {
		$this->hiearchy = $hiearchy;
	}
	
	function getPath() {
		return $this->path;
	}
	
	function setPath($path) {
		$this->path = $path;
	}
	
	function getProperties() {
		return $this->properties;
	}
	
	function setProperties($properties) {
		$this->properties = $properties;
	}
	
	function setRelations($relations) {
	    $this->relations = $relations;
	}
	
	function getRelations() {
	    return $this->relations;
	}
	
}