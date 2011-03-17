<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */

require_once($basePath.'Editor/Classes/Services/ObjectService.php');

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
		$this->relationsFrom[] = array('id'=>$object->getId(),'kind'=>$kind);
		return $this;
	}
	
	function withRelationTo($object,$kind=null) {
		$this->relationsTo[] = array('id'=>$object->getId(),'kind'=>$kind);
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
	
	// TODO: Deprecated
	function withField($field,$value) {
		$this->fields[$field] = $value;
		return $this;
	}

	function withProperty($field,$value) {
		$this->fields[$field] = $value;
		return $this;
	}


	// Getters
	
	function getType() {
	    return $this->type;
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