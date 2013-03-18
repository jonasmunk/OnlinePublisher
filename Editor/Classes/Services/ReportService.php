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
		SettingService::setSetting('system','reports','email',$value);
	}

	function getEmail() {
		return SettingService::getSetting('system','reports','email');
	}
	
	function heartBeat() {
		$latest = intval(SettingService::getSetting('system','reports','latest'));
		Log::debug('Latest: '.$latest);
		$seconds = time() - intval($latest);
		$oneDay = 60 * 60 * 24;
		if ($seconds > $oneDay) {
			Log::debug('Latest run was more than one day ago');
			SettingService::setSetting('system','reports','latest',time());
			ReportService::sendReport();
		} else {
			Log::debug('Will not send report yet: '.DateUtils::formatDuration($seconds));
		}
	}
	
	function sendReport() {
		$url = ConfigurationService::getCompleteBaseUrl();
		
		$email = ReportService::getEmail();
		if (!ValidateUtils::validateEmail($email)) {
			Log::debug('The email is not valid');
			return false;
		}
		$name = '';
		$subject = 'Report from '.$url;
		$body = 'This is a beat from the heart of '.$url;
		return MailService::send($email,$name,$subject,$body);
	}
}