<?
require_once('UserInterface.php');
class SystemInfo {
	
	private static $date = 30;
	private static $month = 12;
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