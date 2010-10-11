<?
require_once('UserInterface.php');
class SystemInfo {
	
	private static $date = 11;
	private static $month = 10;
	private static $year = 2010;
	private static $feedbackMail = "jonasmunk@mac.com";
	private static $feedbackName = "Jonas Munk";
	
	function getDate() {
		$timestamp = mktime(0,0,0,SystemInfo::$month,SystemInfo::$date,SystemInfo::$year);
		return $timestamp;
	}
	
	function getFormattedDate() {
		$timestamp = mktime(0,0,0,SystemInfo::$month,SystemInfo::$date,SystemInfo::$year);
		return UserInterface::presentDate(SystemInfo::getDate());
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