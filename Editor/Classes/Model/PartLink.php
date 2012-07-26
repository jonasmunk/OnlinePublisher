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
require_once($basePath.'Editor/Classes/Services/PartService.php');

class PartLink {
	
	var $id;
	var $partId;
	var $sourceType;
	var $sourceText;
	var $targetType;
	var $targetValue;
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
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