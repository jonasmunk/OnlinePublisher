<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class Search {
	
	var $id;
	var $title;
	
	var $pagesLabel;
	var $pagesEnabled;
	var $pagesDefault;
	var $pagesHidden;
	
	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setTitle($title) {
	    $this->title = $title;
	}

	function getTitle() {
	    return $this->title;
	}
	
	function setPagesLabel($pagesLabel) {
	    $this->pagesLabel = $pagesLabel;
	}

	function getPagesLabel() {
	    return $this->pagesLabel;
	}
	
	function setPagesEnabled($pagesEnabled) {
	    $this->pagesEnabled = $pagesEnabled;
	}

	function getPagesEnabled() {
	    return $this->pagesEnabled;
	}
	
	function setPagesDefault($pagesDefault) {
	    $this->pagesDefault = $pagesDefault;
	}

	function getPagesDefault() {
	    return $this->pagesDefault;
	}
	
	function setPagesHidden($pagesHidden) {
	    $this->pagesHidden = $pagesHidden;
	}

	function getPagesHidden() {
	    return $this->pagesHidden;
	}
	
	function getPagesState() {
		if (!$this->pagesEnabled) {
			return 'inactive';
		}
		if ($this->pagesEnabled && !$this->pagesHidden && !$this->pagesDefault) {
			return 'possible';
		}
		if ($this->pagesEnabled && !$this->pagesHidden && $this->pagesDefault) {
			return 'chosen';
		}
		return 'automatic';
	}
	
	function save() {
		$sql = "update search set ".
			"title=".Database::text($this->title).
			
			",pageslabel=".Database::text($this->pagesLabel).
			",pagesenabled=".Database::boolean($this->pagesEnabled).
			",pagesdefault=".Database::boolean($this->pagesDefault).
			",pageshidden=".Database::boolean($this->pagesHidden).
			
			" where page_id=".$this->id;
		Database::update($sql);
		
		PageService::markChanged($this->id);
	}
	
	function load($id) {
		$sql="select * from search where page_id=".Database::int($id);
		if ($row = Database::getRow($sql)) {
			$obj = new Search();
			$obj->setId(intval($row['page_id']));
			$obj->setTitle($row['title']);
			
			$obj->setPagesLabel($row['pageslabel']);
			$obj->setPagesEnabled($row['pagesenabled'] ? true : false);
			$obj->setPagesDefault($row['pagesdefault'] ? true : false);
			$obj->setPagesHidden($row['pageshidden'] ? true : false);
			
			return $obj;
		}
		return null;
	}
}