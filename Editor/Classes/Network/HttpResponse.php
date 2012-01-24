<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class HttpResponse {
	
	private $statusCode;
	
	function HttpResponse() {
	}

	function setStatusCode($statusCode) {
		$this->statusCode = $statusCode;
	}

	function getStatusCode() {
		return $this->statusCode;
	}
	
	function isSuccess() {
		return $this->statusCode == 200;
	}
}