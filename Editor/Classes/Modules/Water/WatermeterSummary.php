<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Water
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WatermeterSummary {
	var $watermeterId;
	var $number;
	var $street;
	var $zipcode;
	var $city;
	var $email;
	var $phone;
	
	function setWatermeterId($watermeterId) {
	    $this->watermeterId = $watermeterId;
	}

	function getWatermeterId() {
	    return $this->watermeterId;
	}
	
	function setNumber($number) {
	    $this->number = $number;
	}

	function getNumber() {
	    return $this->number;
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
	
	function setEmail($email) {
	    $this->email = $email;
	}

	function getEmail() {
	    return $this->email;
	}
	
	function setPhone($phone) {
	    $this->phone = $phone;
	}

	function getPhone() {
	    return $this->phone;
	}
	
}