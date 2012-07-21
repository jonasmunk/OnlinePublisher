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
		return (isset($CONFIG) && $CONFIG['debug']);
	}
}