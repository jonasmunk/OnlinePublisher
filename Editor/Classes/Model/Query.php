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
	
	function Query($type) {
		$this->type = $type;
	}

	function getType() {
	    return $this->type;
	}

	function getText() {
	    return $this->text;
	}	
	
	function after($type) {
		return new Query($type);
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
	
	function withField($field,$value) {
		$this->fields[$field] = $value;
		return $this;
	}
	
	function search() {
		return ObjectService::search($this);
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