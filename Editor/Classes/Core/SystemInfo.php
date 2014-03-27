<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class SystemInfo {
	
	private static $date = 26;
	private static $month = 3;
	private static $year = 2014;
	private static $feedbackMail = "jonasmunk@mac.com";
	private static $feedbackName = "Jonas Munk";
	
	static function getDate() {
		$timestamp = mktime(0,0,0,SystemInfo::$month,SystemInfo::$date,SystemInfo::$year);
		return $timestamp;
	}
	
	static function getFormattedDate() {
		$timestamp = mktime(0,0,0,SystemInfo::$month,SystemInfo::$date,SystemInfo::$year);
		return Dates::formatDate(SystemInfo::getDate());
	}
	
	static function getTitle() {
		return 'OnlinePublisher '.SystemInfo::getFormattedDate();
	}
	
	static function getFeedbackMail() {
		return SystemInfo::$feedbackMail;
	}
	
	static function getFeedbackName() {
		return SystemInfo::$feedbackName;
	}
}
?>