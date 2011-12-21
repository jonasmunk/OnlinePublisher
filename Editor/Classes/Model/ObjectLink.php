<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');
require_once($basePath.'Editor/Classes/Services/ObjectLinkService.php');

class ObjectLink {
	
	var $id;
	var $type;
	var $value;
	var $position;
	var $text;
	var $objectId;
	var $info;
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setInfo($info) {
	    $this->info = $info;
	}

	function getInfo() {
	    return $this->info;
	}
	
	
	function setType($type) {
	    $this->type = $type;
	}

	function getType() {
	    return $this->type;
	}
	
	function setValue($value) {
	    $this->value = $value;
	}

	function getValue() {
	    return $this->value;
	}
	
	function setPosition($position) {
	    $this->position = $position;
	}

	function getPosition() {
	    return $this->position;
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setObjectId($objectId) {
	    $this->objectId = $objectId;
	}

	function getObjectId() {
	    return $this->objectId;
	}
	
	function toUnicode() {
		$this->text = mb_convert_encoding($this->text, "UTF-8","ISO-8859-1");
		$this->value = mb_convert_encoding($this->value, "UTF-8","ISO-8859-1");
	}

	static $icons = array('file' => 'monochrome/file', 'page' => 'common/page', 'url' => 'monochrome/globe', 'email' => 'monochrome/email');
	
	function getIcon() {
		return ObjectLink::$icons[$this->type];
	}
	
	function search($query = array()) {
		return ObjectLinkService::search($query);
	}
}