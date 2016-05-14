<?php
require_once('../../Editor/Include/Public.php');

//sleep(3);
$number = Request::getString('number');

$summary = WaterusageService::getSummary($number);

if (!$summary) {
	Response::notFound();
	exit;
}

$sql = "select DATE_FORMAT(waterusage.date, '%d-%m-%Y') as `date`,`value`,UNIX_TIMESTAMP(waterusage.date) as time, waterusage.status, waterusage.object_id as id".
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
		if ($time>0) {
			$perWeek = $amount / ($time / 60 / 60 / 24) * 7;
			$row['perweek'] = number_format($perWeek,4,',','.');
		} else {
			$row['perweek'] = '?';
		}
	}
	$row['status'] = intval($row['status']);
	$latest = intval($row['time']);
	$previousValue = intval($row['value']);
}


Response::sendObject(array(
	'usage' => $rows,
	'info' => $summary,
	'graph' => buildChart($number)
));


function buildChart($number) {
	$sql = "select DATE_FORMAT(waterusage.date, '%d-%m-%Y') as `date`,`value`,UNIX_TIMESTAMP(waterusage.date) as time".
	" from waterusage,watermeter where waterusage.`watermeter_id`=watermeter.`object_id` and number = ".Database::text($number)." order by waterusage.`date`";
	$rows = Database::selectAll($sql);
	$entries = array();
	foreach ($rows as &$row) {
		$entries[] = array('key'=>intval($row['time']),'value'=>intval($row['value']),'label' => $row['date']);
	}
	
	
	return array('sets' => array(array('type'=>'line','entries'=> interpolate($entries))));
}

function interpolate($entries) {
	$interpolated = array();
	
	$latestTime = -1;
	$latestValue;
	foreach ($entries as $entry) {
		$time = $entry['key'];
		$value = $entry['value'];
		
		if ($latestTime > 0) {
			$num = $latestTime;
			while ($num < $time) {
				$num += (60*60*24);
				$x = $latestValue + ($value - $latestValue) * ($num - $latestTime) / ($time - $latestTime);
				$interpolated[] = array('key' => $num,'value' => $x, 'label' => strftime('%d-%m-%Y',$num));
			}
		}
		
		$interpolated[] = $entry;
		$latestTime = $time;
		$latestValue = $entry['value'];
	}
	return $interpolated;
}
?>