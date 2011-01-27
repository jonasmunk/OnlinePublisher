<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath."Editor/Libraries/gapi/gapi.class.php");
require_once($basePath."Editor/Classes/Settings.php");

class GoogleAnalytics {
	
	function getUsername() {
		return Settings::getSetting('system','googleanalytics','username');
	}
	
	function setUsername($value) {
		Settings::setSetting('system','googleanalytics','username',$value);
	}
	
	function getPassword() {
		return Settings::getSetting('system','googleanalytics','password');
	}
	
	function setPassword($value) {
		Settings::setSetting('system','googleanalytics','password',$value);
	}
	
	function getProfile() {
		return Settings::getSetting('system','googleanalytics','profile');
	}
	
	function setProfile($value) {
		Settings::setSetting('system','googleanalytics','profile',$value);
	}
	
	function getWebProfile() {
		return Settings::getSetting('system','googleanalytics','webprofile');
	}
	
	function setWebProfile($value) {
		Settings::setSetting('system','googleanalytics','webprofile',$value);
	}
	
	function test() {
		try {
			GoogleAnalytics::getResult(array(
				'dimensions' => array('browser'),
				'metrics' => array('pageviews')
			));
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	function getResult($query) {
		// requestReportData($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30)
		// http://code.google.com/p/gapi-google-analytics-php-interface/wiki/GAPIDocumentation
		
		$ga = new gapi(GoogleAnalytics::getUsername(),GoogleAnalytics::getPassword());

		$ga->requestReportData(GoogleAnalytics::getProfile(),$query['dimensions'],$query['metrics'],$query['sort'],null,$query['startDate'],$query['endDate']);
		return $ga->getResults();
	}
}
?>