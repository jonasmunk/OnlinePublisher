<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Services/SettingService.php');

class OptimizationService {
	
	function addControlWord($word) {
		
	}
	
	function getSettings() {
		$value = SettingService::getSetting('system','optimization','settings');
		$value = StringUtils::toUnicode($value);
		$value = StringUtils::fromJSON($value);
		return $value;
	}
	
	function setSettings($value) {
		$value = StringUtils::toJSON($value);
		$value = StringUtils::fromUnicode($value);
		SettingService::setSetting('system','optimization','settings',$value);
	}
}