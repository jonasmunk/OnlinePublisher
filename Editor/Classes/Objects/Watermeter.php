<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['watermeter'] = array(
	'number' => array('type'=>'string')
);
class Watermeter extends Object {
	var $number;

	function Watermeter() {
		parent::Object('watermeter');
	}

	function load($id) {
		return Object::get($id,'watermeter');
	}
	
	function getIn2iGuiIcon() {
		return "file/generic";
	}
	
	function setNumber($number) {
	    $this->number = $number;
	}

	function getNumber() {
	    return $this->number;
	}
}
?>