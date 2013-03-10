<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class HeartBeatService {
	
	
	function beat() {
		$latest = SettingService::getLatestHeartBeat();
		$now = time();
		if (!$latest) {
			HeartBeatService::run();
			return;
		}
		$duration = $now - intval($latest);
		if ($duration > 60 * 15) {
			HeartBeatService::run();
		} else {
			Log::debug('Skipping beat, duration='.DateUtils::formatDuration($duration));
		}
	}
	
	private function run() {
		Log::debug('Running heart beat');
		SettingService::setLatestHeartBeat(time());
		$url = ConfigurationService::getCompleteBaseUrl();
		MailService::sendToFeedback('Heart beat from '.$url,'This is a beat from the heart of '.$url);
	}
}