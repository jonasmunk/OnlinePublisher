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
	private $direction = 'ascending';
	
	function Query($type) {
		$this->type = $type;
	}

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
	
	function withText($text) {
		$this->text = $text;
		return $this;
	}
	
	function getCustom() {
		return $this->custom;
	}
	
	function getFields() {
		return $this->fields;
	}
	
	function withCustom($key,$value) {
		$this->custom[$key] = $value;
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