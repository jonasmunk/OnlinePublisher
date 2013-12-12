<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class OnlineObjectsService {
	
	static function analyseText($text) {
		$url = OnlineObjectsService::getServiceUrl('language','analyse');
		$request = new WebRequest($url);
		$request->setUnicode(true);
		$request->addParameter('text',$text);
		$response = HttpClient::send($request);
		if (!$response->isSuccess()) {
			return null;
		}
		return Strings::fromJSON($response->getData());
	}
	
	static function getServiceUrl($service,$method,$baseUrl=null) {
		if ($baseUrl==null) {
			$baseUrl = SettingService::getOnlineObjectsUrl();
		}
		return Strings::concatUrl($baseUrl,'v1.0/'.$service.'/'.$method);
	}
	
	static function test($url) {
		$serviceUrl = OnlineObjectsService::getServiceUrl('language','analyse',$url);
		$request = new WebRequest($serviceUrl);
		$request->addParameter('text','Hello world');
		$response = HttpClient::send($request);
		return $response->isSuccess();
	}
}