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

	static function send($request) {
		$body = http_build_query($request->getParameters(),'','&');
		$session = curl_init($request->getUrl());
    $headers = [];
		if ($request->getUnicode()) {
			$headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
		}
    foreach ($request->getHeaders() as $header) {
      $headers[] = $header['name'] . ": " . $header['value'];
    }
		curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($session, CURLOPT_POST, 1);
		curl_setopt($session, CURLOPT_POSTFIELDS, $body);
		curl_setopt($session, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($session, CURLOPT_HEADER, 1);

		$data = curl_exec($session);
		$statusCode = null;
		$errorNumber = curl_errno($session);
		if(!$errorNumber) {
			$info = curl_getinfo($session);
      $statusCode = $info['http_code'];
		} else {
			Log::debug('Error number: '.$errorNumber.' / URL: '.$request->getUrl());
		}
		curl_close($session);
		$response = WebResponse::newFromData($data);
		$response->setStatusCode($statusCode);
    return $response;
	}
}