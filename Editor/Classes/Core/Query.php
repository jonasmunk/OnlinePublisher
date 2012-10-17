<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Core
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Query {
	
	private $type;
	private $text;
	private $custom = array();
	private $fields = array();
	private $ordering = array();
	private $relationsFrom = array();
	private $relationsTo = array();
	private $direction = 'ascending';
	private $windowPage = 0;
	private $windowSize = 100;
	private $createdMin;
	private $ids;
	
	function Query($type) {
		$this->type = $type;
	}
	
	function after($type) {
		return new Query($type);
	}
	
	function orderBy($order) {
		$this->ordering[] = $order;
		return $this;
	}
	
	function orderByCreated() {
		$this->ordering[] = 'created';
		return $this;
	}
	
	function orderByTitle() {
		$this->ordering[] = 'title';
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
	
	function withCreatedMin($createdMin) {
	    $this->createdMin = $createdMin;
		return $this;
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

	function withCustom($key,$value) {
		$this->custom[$key] = $value;
		return $this;
	}
	
	function withWindowPage($page) {
		$this->windowPage = $page;
		return $this;
	}
	function withText($text) {
		$this->text = $text;
		return $this;
	}
	
	function withProperty($field,$value) {
		$this->fields[$field] = $value;
		return $this;
	}
	
	function withoutProperty($field,$value) {
		$this->fields[$field] = array('value'=>$value,'comparison'=>'not');
		return $this;
	}

	function withIds($ids) {
		$this->ids = $ids;
		return $this;
	}
	
	function withPropertyBetween($field,$from,$to) {
		$this->fields[$field] = array('from'=>$from,'to'=>$to);
		return $this;
	}

	// Getters
	
	function getType() {
	    return $this->type;
	}
	
	function getIds() {
		return $this->ids;
	}

	function getText() {
	    return $this->text;
	}	

	function getDirection() {
	    return $this->direction;
	}	
	
	function getOrdering() {
		return $this->ordering;
	}

	function getCreatedMin() {
	    return $this->createdMin;
	}	
	
	function getCustom() {
		return $this->custom;
	}
	
	function getFields() {
		return $this->fields;
	}
	
	function getRelationsTo() {
	    return $this->relationsTo;
	}
	
	function getRelationsFrom() {
	    return $this->relationsFrom;
	}
	
	function getWindowPage() {
		return $this->windowPage;
	}
	
	function withWindowSize($size) {
		$this->windowSize = $size;
		return $this;
	}
	
	function getWindowSize() {
		return $this->windowSize;
	}

	// Actions
	
	function search() {
		return ObjectService::search($this);
	}

	function get() {
		$result = ObjectService::search($this);
		return $result->getList();
	}
	
	function first() {
		$result = ObjectService::search($this);
		$list = $result->getList();
		if (count($list)>0) {
			return $list[0];
		}
		return null;
	}
}