<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */

class RemoteData {
	
	var $file;
	var $age;
	var $success;
	var $hasData;
	
	function setFile($file) {
	    $this->file = $file;
	}

	function getFile() {
	    return $this->file;
	}
	
	function setAge($age) {
	    $this->age = $age;
	}

	function getAge() {
	    return $this->age;
	}
	
	function setSuccess($success) {
	    $this->success = $success;
	}

	function isSuccess() {
	    return $this->success;
	}
	
	function setHasData($hasData) {
	    $this->hasData = $hasData;
	}

	function isHasData() {
	    return $this->hasData;
	}
	
}