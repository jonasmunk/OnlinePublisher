<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');

$query = new StatisticsQuery();
$query -> withTime(Request::getString('time')) -> withResolution(Request::getString('resolution'));

if ($kind=='browsers') {
	$chart = StatisticsService::getVisitsChart($query);
} else {
	$chart = StatisticsService::getVisitsChart($query);
}

Response::sendObject($chart);
?>