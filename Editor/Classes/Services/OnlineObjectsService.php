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
	
	function getServiceUrl($service,$method) {
		$base = SettingService::getOnlineObjectsUrl();
		return StringUtils::concatUrl($base,'service/'.$service.'/'.$method);
	}
}