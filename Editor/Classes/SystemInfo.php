<?
require_once($basePath.'Editor/Classes/Utilities/DateUtils.php');
class SystemInfo {
	
	private static $date = 6;
	private static $month = 2;
	private static $year = 2011;
	private static $feedbackMail = "jonasmunk@mac.com";
	private static $feedbackName = "Jonas Munk";
	
	function getDate() {
		$timestamp = mktime(0,0,0,SystemInfo::$month,SystemInfo::$date,SystemInfo::$year);
		return $timestamp;
	}
	
	function getFormattedDate() {
		$timestamp = mktime(0,0,0,SystemInfo::$month,SystemInfo::$date,SystemInfo::$year);
		return DateUtils::formatDate(SystemInfo::getDate());
	}
	
	function getTitle() {
		return 'OnlinePublisher '.SystemInfo::getFormattedDate();
	}
	
	function getFeedbackMail() {
		return SystemInfo::$feedbackMail;
	}
	
	function getFeedbackName() {
		return SystemInfo::$feedbackName;
	}
}
?>