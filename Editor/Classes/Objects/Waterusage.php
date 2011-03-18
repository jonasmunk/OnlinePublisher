<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['waterusage'] = array(
	'number' => array('type'=>'string'),
	'watermeterId'   => array('type'=>'int','column'=>'watermeter_id'),
	'year'   => array('type'=>'int'),
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
	
	
	function setNumber($number) {
	    $this->title = $number;
	    $this->number = $number;
	}

	function getNumber() {
	    return $this->number;
	}
	
	function setYear($year) {
	    $this->year = $year;
	}

	function getYear() {
	    return $this->year;
	}
	
	function setValue($value) {
	    $this->value = $value;
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
}
?>