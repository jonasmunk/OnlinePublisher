<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class HeartBeatService {
	
	
	function beat() {
		$latest = SettingService::getLatestHeartBeat();
		if (!$latest) {
			HeartBeatService::run();
			return;
		}
		$duration = time() - intval($latest);
		if ($duration > 60 * 15) {
			HeartBeatService::run();
		} else {
			Log::debug('Skipping beat, duration='.DateUtils::formatDuration($duration));
		}
	}
	
	private function run() {
		SettingService::setLatestHeartBeat(time());
		ReportService::heartBeat();
		
		$sources = Query::after('calendarsource')->orderBy('title')->get();
		foreach ($sources as $source) {
			$source->synchronize();
		}
	}
}