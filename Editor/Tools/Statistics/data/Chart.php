<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');

$query = new StatisticsQuery();
$query -> withTime(Request::getString('time'));

if ($kind=='browsers') {
	$chart = StatisticsService::getChart($query);
} else {
	$chart = StatisticsService::getChart($query);
}

Response::sendObject($chart);
?>