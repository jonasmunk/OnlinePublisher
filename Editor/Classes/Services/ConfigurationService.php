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
	
	function isUrlRewrite() {
		return isset($GLOBALS['CONFIG']) && @$GLOBALS['CONFIG']['urlrewrite'];
	}
	
	function getBaseUrl() {
		global $CONFIG,$baseUrl;
		if (isset($CONFIG) && isset($CONFIG['baseUrl'])) {
			return $CONFIG['baseUrl'];
		}
		return $baseUrl;
	}
	
	function getDatabase() {
		global $CONFIG,$database_host, $database_user,$database_password,$database;
		if (isset($CONFIG) && isset($CONFIG['database'])) {
			return $CONFIG['database'];
		}
		return array(
			'host' => $database_host,
			'user' => $database_user,
			'password' => $database_password,
			'database' => $database
		);
	}
	
	function getSuperUsername() {
		global $CONFIG,$superUser;
		if (isset($CONFIG) && isset($CONFIG['super']) && isset($CONFIG['user'])) {
			return $CONFIG['super']['user'];
		}
		return $superUser;
	}
	
	function getSuperPassword() {
		global $CONFIG,$superPassword;
		if (isset($CONFIG) && isset($CONFIG['super']) && isset($CONFIG['password'])) {
			return $CONFIG['super']['password'];
		}
		return $superPassword;
	}
	
	function getCompleteBaseUrl() {
		$url = ConfigurationService::getBaseUrl();
		if (!StringUtils::startsWith($url,'http')) {
			$url = 'http://localhost'.$url;
		}
		return $url;
	}
}