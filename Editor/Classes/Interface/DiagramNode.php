<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Interface
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class DiagramNode {

	var $id;
	var $title;
	var $properties = array();
	
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
	
	function setProperties($properties) {
	    $this->properties = $properties;
	}
	
	function getProperties() {
	    return $this->properties;
	}
	
}
?>