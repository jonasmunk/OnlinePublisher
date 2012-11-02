<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$chart = StatisticsService::getChart(array('days'=>21));

Response::sendObject($chart);
?>