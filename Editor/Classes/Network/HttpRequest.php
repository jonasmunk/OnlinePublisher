<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class HttpRequest {
	
	private $url;
	private $parameters;
	
	function HttpRequest($url=null) {
		$this->url = $url;
	}

	function setUrl($url) {
		$this->url = $url;
	}
	
	function getUrl() {
		return $this->url;
	}

	function setParameters($parameters) {
		$this->parameters = $parameters;
	}
	
	function getParameters() {
		return $this->parameters;
	}

}