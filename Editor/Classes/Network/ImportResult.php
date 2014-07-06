<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class ImportResult {
	
	var $success = false;
	var $message = null;
	var $object = null;
	
	static function fail($message) {
		$result = new ImportResult();
		$result->setMessage($message);
		return $result;
	}
	
	function setSuccess($success) {
	    $this->success = $success;
	}

	function getSuccess() {
	    return $this->success;
	}
	
	function setMessage($message) {
	    $this->message = $message;
	}

	function getMessage() {
	    return $this->message;
	}
	
	function setObject($object) {
	    $this->object = $object;
	}

	function getObject() {
	    return $this->object;
	}
	
}
?>