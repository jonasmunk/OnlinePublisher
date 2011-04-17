<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.News
 */

class NewsArticle {
	var $title;
	var $text;
	var $summary;
	var $pageBlueprintId;
	var $startDate;
	var $endDate;
	var $linkText;
	var $groupIds;
	
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
	
	function setGroupIds($groupIds) {
	    $this->groupIds = $groupIds;
	}

	function getGroupIds() {
	    return $this->groupIds;
	}
	
	function setStartDate($startDate) {
	    $this->startDate = $startDate;
	}
	
	function setEndDate($endDate) {
	    $this->endDate = $endDate;
	}

	function getEndDate() {
	    return $this->endDate;
	}
	

	function getStartDate() {
	    return $this->startDate;
	}
	
	
	function setPageBluePrintId($pageBluePrintId) {
	    $this->pageBluePrintId = $pageBluePrintId;
	}

	function getPageBluePrintId() {
	    return $this->pageBluePrintId;
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setSummary($summary) {
	    $this->summary = $summary;
	}

	function getSummary() {
	    return $this->summary;
	}
	
}