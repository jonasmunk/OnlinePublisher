<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['PartLink'] = array(
	'table' => 'part_link',
	'properties' => array(
		'partId' => array('type'=>'int','relation'=>array('class'=>'Part','property'=>'id')),
		'targetValue' => array('type'=>'text','relations'=>array(
			array('class'=>'Page','property'=>'id'),
			array('class'=>'File','property'=>'id')
			)
		)
	)
);
class PartLink extends Entity {
	
	var $partId;
	var $sourceType;
	var $sourceText;
	var $targetType;
	var $targetValue;
		
	function setPartId($partId) {
	    $this->partId = $partId;
	}

	function getPartId() {
	    return $this->partId;
	}
	
	
	function setSourceType($sourceType) {
	    $this->sourceType = $sourceType;
	}

	function getSourceType() {
	    return $this->sourceType;
	}
	
	function setSourceText($sourceText) {
	    $this->sourceText = $sourceText;
	}

	function getSourceText() {
	    return $this->sourceText;
	}
	
	
	function setTargetType($targetType) {
	    $this->targetType = $targetType;
	}

	function getTargetType() {
	    return $this->targetType;
	}
	
	function setTargetValue($targetValue) {
	    $this->targetValue = $targetValue;
	}

	function getTargetValue() {
	    return $this->targetValue;
	}
	
	function save() {
		PartService::saveLink($this);
	}
}