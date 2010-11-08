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
	
	function withCustom($key,$value) {
		$this->custom[$key] = $value;
		return $this;
	}
	
	function search() {
		return ObjectService::search($this);
	}
}