<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}


Entity::$schema['ObjectLink'] = array(
	'table' => 'object_link',
	'properties' => array(
		'objectId' => array('type'=>'int','relation'=>array('class'=>'Object','property'=>'id')),
		'value' => array('type'=>'int','relations'=>array(
			array('class'=>'Page','property'=>'id'),
			array('class'=>'File','property'=>'id')
			)
		)
	)
);
class ObjectLink extends Entity {
	
	var $type;
	var $value;
	var $position;
	var $text;
	var $objectId;
	var $info;
		
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
	
	static $icons = array('file' => 'monochrome/file', 'page' => 'common/page', 'url' => 'monochrome/globe', 'email' => 'monochrome/email');
	
	function getIcon() {
		return ObjectLink::$icons[$this->type];
	}
	
	function search($query = array()) {
		return ObjectLinkService::search($query);
	}
}