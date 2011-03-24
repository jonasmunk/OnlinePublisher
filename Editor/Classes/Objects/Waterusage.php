<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['waterusage'] = array(
	'watermeterId'   => array('type'=>'int','column'=>'watermeter_id'),
	'value'  => array('type'=>'int'),
	'date'  => array('type'=>'datetime')
);
class Waterusage extends Object {
	var $watermeterId;
	var $number;
	var $year;
	var $value;
	var $date;

	function Waterusage() {
		parent::Object('waterusage');
	}

	function load($id) {
		return Object::get($id,'waterusage');
	}
	
	function getIn2iGuiIcon() {
		return "common/water";
	}
	
	function setWatermeterId($watermeterId) {
	    $this->watermeterId = $watermeterId;
	}

	function getWatermeterId() {
	    return $this->watermeterId;
	}
	
	function setValue($value) {
	    $this->value = $value;
		$this->title = $value;
	}

	function getValue() {
	    return $this->value;
	}
	
	function setDate($date) {
	    $this->date = $date;
	}

	function getDate() {
	    return $this->date;
	}
	
	function sub_index() {
		$words = array($this->number);
		if ($meter = Watermeter::load($this->watermeterId)) {
			return $meter->getNumber();
		}
		return StringUtils::buildIndex($words);
	}
}
?>