<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.News
 */

class NewsArticle {
	var $title;
	var $pageBlueprintId;
	var $linkText;
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setLinkText($linkText) {
	    $this->linkText = $linkText;
	}

	function getLinkText() {
	    return $this->linkText;
	}
	
	
	function setPageBluePrintId($pageBluePrintId) {
	    $this->pageBluePrintId = $pageBluePrintId;
	}

	function getPageBluePrintId() {
	    return $this->pageBluePrintId;
	}
	
}