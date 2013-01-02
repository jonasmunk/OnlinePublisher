<?php
require_once('../../Editor/Include/Public.php');

$number = Request::getString('number');

$sql = "select DATE_FORMAT(waterusage.date, '%d-%m-%Y') as `date`,`value`,UNIX_TIMESTAMP(waterusage.date) as time".
	" from waterusage,watermeter where waterusage.`watermeter_id`=watermeter.`object_id` and number = ".Database::text($number)." order by waterusage.`date`";
$rows = Database::selectAll($sql);

$latest = 0;
$previousValue;
foreach ($rows as &$row) {
	if ($latest==0) {
		$row['perweek'] = '?';
	} else {
		$time = intval($row['time']) - $latest;
		$amount = intval($row['value']) - $previousValue;
		$row['perweek'] = number_format($amount / ($time / 60 / 60 / 24) * 7,4);
	}
	$latest = intval($row['time']);
	$previousValue = intval($row['value']);
}

$summary = WaterusageService::getSummary($number);

Response::sendObject(array(
	'usage' => $rows,
	'info' => $summary
));
?>