<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Object.php');

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

	function load($id) {
		return Object::get($id,'address');
	}
	
	function getIn2iGuiIcon() {
		return "file/generic";
	}
	
	function setStreet($street) {
	    $this->street = $street;
	}

	function getStreet() {
	    return $this->street;
	}
	
	
	function setZipcode($zipcode) {
	    $this->zipcode = $zipcode;
	}

	function getZipcode() {
	    return $this->zipcode;
	}
	
	function setCity($city) {
	    $this->city = $city;
	}

	function getCity() {
	    return $this->city;
	}
	
	function setCountry($country) {
	    $this->country = $country;
	}

	function getCountry() {
	    return $this->country;
	}
	
}
?>