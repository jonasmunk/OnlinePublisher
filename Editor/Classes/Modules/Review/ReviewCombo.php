<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Review
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class ReviewCombo {
	
	private $pageTitle;
	private $pageId;
	private $accepted;
	
	function setPageTitle($pageTitle) {
	    $this->pageTitle = $pageTitle;
	}

	function getPageTitle() {
	    return $this->pageTitle;
	}
	
	function setPageId($pageId) {
	    $this->pageId = $pageId;
	}

	function getPageId() {
	    return $this->pageId;
	}
	
	function setAccepted($accepted) {
	    $this->accepted = $accepted;
	}

	function getAccepted() {
	    return $this->accepted;
	}
	
}
?>