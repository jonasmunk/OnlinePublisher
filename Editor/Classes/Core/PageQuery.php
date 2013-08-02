<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Core
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class PageQuery {
	
	private $text;
	private $ordering = array();
	private $direction = 'ascending';
	private $windowPage = 0;
	private $windowSize = 100;
	private $relationsFrom = array();
	private $relationsTo = array();
	
	function rows() {
		return new PageQuery();
	}

	function getRows() {
		return new PageQuery();
	}
	
	function withText($text) {
		$this->text = $text;
		return $this;
	}
	
	function withWindowPage($page) {
		$this->windowPage = $page;
		return $this;
	}
	
	function withWindowSize($size) {
		$this->windowSize = $size;
		return $this;
	}
	
	function orderBy($order) {
		if (Strings::isNotBlank($order)) {
			$this->ordering[] = $order;
		}
		return $this;
	}
	
	function ascending() {
		$this->direction = 'ascending';
		return $this;
	}
	
	function descending() {
		$this->direction = 'descending';
		return $this;
	}
	
	function withDirection($direction) {
		$this->direction = $direction=='ascending' ? 'ascending' : 'descending';
		return $this;
	}
	
	function getText() {
		return $this->text;
	}
	
	function getWindowSize() {
		return $this->windowSize;
	}
	
	function getWindowPage() {
		return $this->windowPage;
	}
	
	function getDirection() {
		return $this->direction;
	}
	
	function getOrdering() {
		return $this->ordering;
	}
	
	function withRelationFrom($object,$kind=null) {
		$id = is_int($object) ? $object : $object->getId();
		$this->relationsFrom[] = array('id'=>$id,'kind'=>$kind,'fromType'=>'object');
		return $this;
	}
	
	function withRelationFromPage($page,$kind=null) {
		$id = is_int($page) ? $page : $page->getId();
		$this->relationsFrom[] = array('id'=>$id,'kind'=>$kind,'fromType'=>'page');
		return $this;
	}
	
	function withRelationTo($object,$kind=null) {
		$id = is_int($object) ? $object : $object->getId();
		$this->relationsTo[] = array('id'=>$id,'kind'=>$kind,'toType'=>'object');
		return $this;
	}
	
	function withRelationToPage($page,$kind=null) {
		$id = is_int($page) ? $page : $page->getId();
		$this->relationsTo[] = array('id'=>$id,'kind'=>$kind,'toType'=>'page');
		return $this;
	}
	
	function getRelationsTo() {
	    return $this->relationsTo;
	}
	
	function getRelationsFrom() {
	    return $this->relationsFrom;
	}
	
	function search() {
		return PageService::search($this);
	}
	
	function asList() {
		return $this->search()->getList();
	}
	
	function first() {
		$list = $this->search()->getList();
		if ($list) {
			return $list[0];
		}
		return null;
	}
}