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
	private $sourcePage;
	private $onlyWarnings = false;
	private $textCheck = false;
	
	function withSourceType($sourceType) {
		$this->sourceType = $sourceType;
		return $this;
	}
	
	function withTargetType($targetType) {
		$this->targetType = $targetType;
		return $this;
	}
	
	function withSourcePage($sourcePage) {
		$this->sourcePage = $sourcePage;
		return $this;
	}

	function withOnlyWarnings() {
		$this->onlyWarnings = true;
		return $this;
	}
	
	function withTextCheck() {
		$this->textCheck = true;
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
	
	function getTextCheck() {
	    return $this->textCheck;
	}
	
	function getSourcePage() {
	    return $this->sourcePage;
	}
	
}