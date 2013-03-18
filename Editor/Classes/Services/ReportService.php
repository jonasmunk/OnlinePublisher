<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ReportService {
	
	function setEmail($value) {
		SettingService::setSetting('system','reports','service',$value);
	}

	
}