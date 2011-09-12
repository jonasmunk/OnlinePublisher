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
	
	function toUnicode() {
		$this->number = StringUtils::toUnicode($this->number);
		$this->street = StringUtils::toUnicode($this->street);
		$this->zipcode = StringUtils::toUnicode($this->zipcode);
		$this->city = StringUtils::toUnicode($this->city);
	}
	
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
	
}