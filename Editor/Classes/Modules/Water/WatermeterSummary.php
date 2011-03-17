<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Water
 */

class WatermeterSummary {
	var $watermeterId;
	var $number;
	var $street;
	var $zipcode;
	var $city;
	var $usages;
	
	function toUnicode() {
		$this->number = StringUtils::toUnicode($this->number);
		$street = StringUtils::toUnicode($this->street);
		$zipcode = StringUtils::toUnicode($this->zipcode);
		$city = StringUtils::toUnicode($this->city);
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
	
	function setUsages($usages) {
	    $this->usages = $usages;
	}

	function getUsages() {
	    return $this->usages;
	}
	
	
}