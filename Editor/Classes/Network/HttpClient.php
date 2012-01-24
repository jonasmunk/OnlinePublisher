<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class HttpClient {

	function request($request) {
		$body = http_build_query($request->getParameters(),'','&');
	
		$session = curl_init($request->getUrl());
		curl_setopt ($session, CURLOPT_POST, 1);
		curl_setopt ($session, CURLOPT_POSTFIELDS, $body);
		curl_setopt ($session, CURLOPT_FOLLOWLOCATION, 1);
		curl_exec ($session);
		curl_close ($session);
		return true;
	}
}