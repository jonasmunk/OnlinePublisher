<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Feed {
	
	var $items = array();
	var $format;
	var $title;
	var $link;
	var $language;
	var $description;
	var $copyright;
	var $lastBuildDate;
	var $pubDate;
	var $docs;
	var $generator;
	var $webMaster;
	var $managingEditor;
	var $ttl;
	var $image;
	var $rating;
	
	
	function Feed() {
	}
	
	function addItem($item) {
		$this->items[] = $item;
	}
	
	function getItems() {
		return $this->items;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setCopyright($copyright) {
	    $this->copyright = $copyright;
	}

	function getCopyright() {
	    return $this->copyright;
	}
	
	
	function setDescription($description) {
	    $this->description = $description;
	}

	function getDescription() {
	    return $this->description;
	}
	
	
	function setLanguage($language) {
	    $this->language = $language;
	}

	function getLanguage() {
	    return $this->language;
	}
	
	function setLink($link) {
	    $this->link = $link;
	}

	function getLink() {
	    return $this->link;
	}
	
	function setPubDate($pubDate) {
	    $this->pubDate = $pubDate;
	}

	function getPubDate() {
	    return $this->pubDate;
	}
	
	function setLastBuildDate($lastBuildDate) {
	    $this->lastBuildDate = $lastBuildDate;
	}

	function getLastBuildDate() {
	    return $this->lastBuildDate;
	}
	
	function setDocs($docs) {
	    $this->docs = $docs;
	}

	function getDocs() {
	    return $this->docs;
	}
	
	function setGenerator($generator) {
	    $this->generator = $generator;
	}

	function getGenerator() {
	    return $this->generator;
	}
	
	function setWebMaster($webMaster) {
	    $this->webMaster = $webMaster;
	}

	function getWebMaster() {
	    return $this->webMaster;
	}
	
	function setManagingEditor($managingEditor) {
	    $this->managingEditor = $managingEditor;
	}

	function getManagingEditor() {
	    return $this->managingEditor;
	}
	
	function setTtl($ttl) {
	    $this->ttl = $ttl;
	}

	function getTtl() {
	    return $this->ttl;
	}
	
	function setImage($image) {
	    $this->image = $image;
	}

	function getImage() {
	    return $this->image;
	}
	
	function setRating($rating) {
	    $this->rating = $rating;
	}

	function getRating() {
	    return $this->rating;
	}
	
}
?>