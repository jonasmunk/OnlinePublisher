<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$patterns = array(
	'day'=>array('sql' => '%Y%m%d','php' => 'Ymd'),
	'hour'=>array('sql' => '%Y%m%d%H','php' => 'YmdH')
);

$resolution = 'day';
$days = 90;

$sql = 'SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, "'.$patterns[$resolution]['sql'].'") as `key`,date_format(statistics.time, "%e") as label FROM statistics where statistics.time>DATE_SUB(now(), INTERVAL '.$days.' DAY)  group by `key` order by `key`';
$rows = Database::selectAll($sql,'key');
$rows = fillGaps($rows,$days);
$sets = array();
$dimensions = array('sessions','ips','hits');


foreach ($dimensions as $dim) {
	$entres = array();
	foreach ($rows as $row) {
		$entries[$row['key']] = $row[$dim];
	}
	$sets[] = array('type'=>'line','entries'=>$entries);
}

Response::sendObject(array('sets'=>$sets));

function fillGaps($rows,$days) {
	global $patterns,$resolution;
	$filled = array();
	$now = time();
	for ($i=$days; $i >= 0; $i--) {
		if ($resolution=='day') {
			$date = DateUtils::addDays($now,$i*-1);
		} else {
			$date = DateUtils::addHours($now,$i*-1);
		}
		$key = date($patterns[$resolution]['php'],$date);
		if (array_key_exists($key,$rows)) {
			$filled[$key] = $rows[$key];
		} else {
			$filled[$key] = array('hits'=>0,'sessions'=>0,'ips'=>0,'key'=>$key,'label'=>date('j',$date));
		}
	}
	return $filled;
}
?>