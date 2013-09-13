<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath."Editor/Libraries/gapi/gapi.class.php");

class GoogleAnalytics {
	
	static function getUsername() {
		return SettingService::getSetting('system','googleanalytics','username');
	}
	
	static function setUsername($value) {
		SettingService::setSetting('system','googleanalytics','username',$value);
	}
	
	static function getPassword() {
		return SettingService::getSetting('system','googleanalytics','password');
	}
	
	static function setPassword($value) {
		SettingService::setSetting('system','googleanalytics','password',$value);
	}
	
	static function getProfile() {
		return SettingService::getSetting('system','googleanalytics','profile');
	}
	
	static function setProfile($value) {
		SettingService::setSetting('system','googleanalytics','profile',$value);
	}
	
	static function getWebProfile() {
		return SettingService::getSetting('system','googleanalytics','webprofile');
	}
	
	static function setWebProfile($value) {
		SettingService::setSetting('system','googleanalytics','webprofile',$value);
	}
	
	static function test() {
		try {
			GoogleAnalytics::getResult(array(
				'dimensions' => array('browser'),
				'metrics' => array('pageviews')
			));
			return true;
		} catch (Exception $e) {
			Log::debug($e);
			return false;
		}
	}
	
	static function getResult($query) {
		// requestReportData($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30)
		// http://code.google.com/p/gapi-google-analytics-php-interface/wiki/GAPIDocumentation
		
		$ga = new gapi(GoogleAnalytics::getUsername(),GoogleAnalytics::getPassword());

		$ga->requestReportData(GoogleAnalytics::getProfile(),$query['dimensions'],$query['metrics'],$query['sort'],null,$query['startDate'],$query['endDate']);
		return $ga->getResults();
	}
}
?>