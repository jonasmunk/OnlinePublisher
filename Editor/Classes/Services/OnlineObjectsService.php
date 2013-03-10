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
	
	function analyseText($text) {
		$url = OnlineObjectsService::getServiceUrl('language','analyse');
		$request = new HttpRequest($url);
		$request->addParameter('text',$text);
		$response = HttpClient::send($request);
		if (!$response->isSuccess()) {
			return null;
		}
		return StringUtils::fromUnicode(StringUtils::fromJSON($response->getData()));
	}
	
	function getServiceUrl($service,$method,$baseUrl=null) {
		if ($baseUrl==null) {
			$baseUrl = SettingService::getOnlineObjectsUrl();
		}
		return StringUtils::concatUrl($baseUrl,'v1.0/'.$service.'/'.$method);
	}
	
	function test($url) {
		$serviceUrl = OnlineObjectsService::getServiceUrl('language','analyse',$url);
		$request = new HttpRequest($serviceUrl);
		$request->addParameter('text','Hello world');
		$response = HttpClient::send($request);
		return $response->isSuccess();
	}
}