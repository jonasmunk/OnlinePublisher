<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ConfigurationService {
	
	function isDebug() {
		global $CONFIG;
		return (isset($CONFIG) && isset($CONFIG['debug']) && $CONFIG['debug']==true);
	}
	
	function getBaseUrl() {
		global $CONFIG,$baseUrl;
		if (isset($CONFIG) && isset($CONFIG['baseUrl'])) {
			return $CONFIG['baseUrl'];
		}
		return $baseUrl;
	}
	
	function getCompleteBaseUrl() {
		$url = ConfigurationService::getBaseUrl();
		if (!StringUtils::startsWith($url,'http')) {
			$url = 'http://localhost'.$url;
		}
		return $url;
	}
}