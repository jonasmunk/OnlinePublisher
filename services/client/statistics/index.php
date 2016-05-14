<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Log
 */
require_once '../../../Editor/Include/Client.php';


$query = new StatisticsQuery();
$query->setStartTime(Dates::addDays(time(),-30));
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

Response::sendObject($result);



?>