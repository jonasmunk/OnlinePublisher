<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class OptimizationService {
	
	static function addControlWord($word) {
		
	}
	
	static function getSettings() {
		$value = SettingService::getSetting('system','optimization','settings');
		$value = Strings::fromJSON($value);
		$value = Strings::fromUnicode($value);
		return $value;
	}
	
	static function setSettings($value) {
		$value = Strings::toUnicode($value);
		$value = Strings::toJSON($value);
		SettingService::setSetting('system','optimization','settings',$value);
	}
}