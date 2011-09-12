<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
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
		return "common/gauge";
	}
	
	function setNumber($number) {
	    $this->number = $number;
		$this->setTitle($number);
	}

	function getNumber() {
	    return $this->number;
	}
	
	function sub_index() {
		$address = Query::after('address')->withRelationFrom($this)->first();
		if ($address) {
			return $address->getIndex();
		}
	}
}
?>