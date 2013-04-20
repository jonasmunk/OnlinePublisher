<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ClientService {


	function getStatistics() {
		$query = new StatisticsQuery();
		$query->setStartTime(DateUtils::addDays(time(),-30));
		$stats = StatisticsService::searchVisits($query);

		$result = array();

		foreach ($stats as $stat) {
			$obj = new stdClass;
			$obj->date = $stat['key'];
			$obj->hits = intval($stat['hits']);
			$obj->ips = intval($stat['ips']);
			$obj->sessions = $stat['sessions'];
			$result[] = $obj;
		}
		return $result;
	}
}
