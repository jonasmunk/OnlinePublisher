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
	
	function getSuperUsername() {
		global $superUser;
		return $superUser;
	}
	
	function getSuperPassword() {
		global $superPassword;
		return $superPassword;
	}
}