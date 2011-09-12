<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Links
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class LinkInfo {
	
	private $id;
	private $pageId;
	private $partId;
	private $sourceType;
	private $sourceText;
	private $alternative;
	private $targetType;
	private $targetValue;
	private $targetId;
	private $targetTitle;
	private $targetIcon;
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
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
	
	function setAlternative($alternative) {
	    $this->alternative = $alternative;
	}

	function getAlternative() {
	    return $this->alternative;
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

	function setTargetTitle($targetTitle) {
	    $this->targetTitle = $targetTitle;
	}

	function getTargetTitle() {
	    return $this->targetTitle;
	}
	
	function setTargetId($targetId) {
	    $this->targetId = $targetId;
	}

	function getTargetId() {
	    return $this->targetId;
	}
	
	function setTargetIcon($targetIcon) {
	    $this->targetIcon = $targetIcon;
	}

	function getTargetIcon() {
	    return $this->targetIcon;
	}
	
	function setPartId($partId) {
	    $this->partId = $partId;
	}

	function getPartId() {
	    return $this->partId;
	}
	
}