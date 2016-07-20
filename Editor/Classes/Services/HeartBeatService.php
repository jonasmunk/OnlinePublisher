<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class HeartBeatService {

	static function beat() {
		$latest = SettingService::getLatestHeartBeat();
		if (!$latest) {
			HeartBeatService::run();
			return;
		}
		$duration = time() - intval($latest);
		if ($duration > 60 * 5) {
			HeartBeatService::run();
		} else {
			Log::debug('Skipping beat, duration='.Dates::formatDuration($duration));
		}
	}

  // TODO: Is this safe?
  static function forceRun() {
    HeartBeatService::run();
  }

	private static function run() {
		SettingService::setLatestHeartBeat(time());
		ReportService::heartBeat();

		$sources = Query::after('calendarsource')->orderBy('title')->get();
		foreach ($sources as $source) {
			$source->synchronize();
		}

    HeartBeatService::executeListeners();
	}

	private static function executeListeners() {
    $listeners = Query::after('listener')->withProperty('event','time')->orderBy('title')->get();
    foreach ($listeners as $listener) {
      if (time() - $listener->getLatestExecution() > $listener->getInterval()) {
        $flow = Query::after('workflow')->withRelationFrom($listener)->first();
        if ($flow) {
          WorkflowService::runWorkflow($flow);
        }

        $listener->setLatestExecution(time());
        $listener->save();
        $listener->publish();
      }
    }
  }

}