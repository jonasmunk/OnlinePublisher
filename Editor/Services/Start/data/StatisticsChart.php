<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$query = new StatisticsQuery();
$query->setStartTime(Dates::addDays(time(),-21));
$query->withResolution('daily');

$chart = StatisticsService::getVisitsChart($query);

Response::sendObject($chart);
?>