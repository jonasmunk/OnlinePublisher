<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Links
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class LinkQuery {
	
	private $targetType;
	private $sourceType;
	private $onlyWarnings = false;
	
	function withSourceType($sourceType) {
		$this->sourceType = $sourceType;
		return $this;
	}
	
	function withTargetType($targetType) {
		$this->targetType = $targetType;
		return $this;
	}
	
	function withOnlyWarnings() {
		$this->onlyWarnings = true;
		return $this;
	}
	
	function getTargetType() {
	    return $this->targetType;
	}
	
	function getSourceType() {
	    return $this->sourceType;
	}
	
	function getOnlyWarnings() {
	    return $this->onlyWarnings;
	}
	
	
}