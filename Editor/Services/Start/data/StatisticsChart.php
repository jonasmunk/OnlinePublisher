<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$query = new StatisticsQuery();
$query->setStartTime(DateUtils::addDays(time(),-21));

$chart = StatisticsService::getChart($query);

Response::sendObject($chart);
?>