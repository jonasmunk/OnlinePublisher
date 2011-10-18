<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Links
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class LinkView {
	
	static $NOT_FOUND = 'notfound';
	static $INVALID = 'invalid';
	
	private $sourceType;
	private $sourceId;
	private $sourceTitle;
	
	private $sourceText;
	
	private $targetType;
	private $targetId;
	private $targetTitle;
	
	private $status;
	
	function setSourceType($sourceType) {
	    $this->sourceType = $sourceType;
	}

	function getSourceType() {
	    return $this->sourceType;
	}
	
	function setSourceId($sourceId) {
	    $this->sourceId = $sourceId;
	}

	function getSourceId() {
	    return $this->sourceId;
	}
	
	function setSourceTitle($sourceTitle) {
	    $this->sourceTitle = $sourceTitle;
	}

	function getSourceTitle() {
	    return $this->sourceTitle;
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
	
	function setTargetId($targetId) {
	    $this->targetId = $targetId;
	}

	function getTargetId() {
	    return $this->targetId;
	}
	
	function setTargetTitle($targetTitle) {
	    $this->targetTitle = $targetTitle;
	}

	function getTargetTitle() {
	    return $this->targetTitle;
	}
	
	function setStatus($status) {
	    $this->status = $status;
	}

	function getStatus() {
	    return $this->status;
	}
	
}