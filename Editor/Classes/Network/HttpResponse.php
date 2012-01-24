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

	function getStatusCode() {
		return $this->statusCode;
	}
}