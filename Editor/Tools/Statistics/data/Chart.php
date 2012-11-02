<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');

if ($kind=='browsers') {
	$chart = StatisticsService::getChart(array('days'=>10));
} else {
	$chart = StatisticsService::getChart(array('days'=>90));
}

Response::sendObject($chart);
?>