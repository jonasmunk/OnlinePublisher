<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');
require_once($basePath.'Editor/Classes/Model/Object.php');

Object::$schema['address'] = array(
	'street' => array('type'=>'string'),
	'zipcode' => array('type'=>'string'),
	'city' => array('type'=>'string'),
	'country' => array('type'=>'string')
);
class Address extends Object {
	var $street;
	var $zipcode;
	var $city;
	var $country;

	function Address() {
		parent::Object('address');
	}
	
	function _updateTitle() {
		$this->title = $this->toString();
	}

	function load($id) {
		return Object::get($id,'address');
	}
	
	function getIn2iGuiIcon() {
		return "geo/map";
	}
	
	function setStreet($street) {
	    $this->street = $street;
		$this->_updateTitle();
	}

	function getStreet() {
	    return $this->street;
	}
	
	function setZipcode($zipcode) {
	    $this->zipcode = $zipcode;
		$this->_updateTitle();
	}

	function getZipcode() {
	    return $this->zipcode;
	}
	
	function setCity($city) {
	    $this->city = $city;
		$this->_updateTitle();
	}

	function getCity() {
	    return $this->city;
	}
	
	function setCountry($country) {
	    $this->country = $country;
		$this->_updateTitle();
	}

	function getCountry() {
	    return $this->country;
	}
	
	function toString() {
		$sb = new StringBuilder($this->street);
		$sb->separator(', ');
		return $sb->separator(', ')->append($this->zipcode)->separator(', ')->append($this->city)->separator(', ')->append($this->country)->toString();
	}
	
	function sub_index() {
		return StringUtils::buildIndex(array($this->street,$this->zipcode,$this->city,$this->country));
	}
}
?>