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

	function send($request) {
		$body = http_build_query($request->getParameters(),'','&');
	
		$session = curl_init($request->getUrl());
		curl_setopt ($session, CURLOPT_POST, 1);
		curl_setopt ($session, CURLOPT_POSTFIELDS, $body);
		curl_setopt ($session, CURLOPT_FOLLOWLOCATION, 1);
		curl_exec ($session);
		$info = null;
		if(!curl_errno($session))
		{
			$info = curl_getinfo($session);
			Log::debug($info);
			Log::debug('Status: '.$info['http_code']);
		}
		curl_close ($session);
		$response = new HttpResponse();
		if ($info!==null) {
			$response->setStatusCode($info['http_code']);
		}
		return $response;
	}
}