<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$days = 21;

$sql = 'SELECT count(distinct statistics.session) as sessions, count(distinct statistics.ip) as ips, count(statistics.id) as hits,date_format(statistics.time, "%Y%m%d") as `key`,date_format(statistics.time, "%e") as label FROM statistics where statistics.time>DATE_SUB(now(), INTERVAL '.$days.' DAY)  group by label order by `key`';
$rows = Database::selectAll($sql,'label');
$rows = fillGaps($rows,$days);
$sets = array();
$dimensions = array('sessions','ips','hits');

foreach ($dimensions as $dim) {
	$entres = array();
	foreach ($rows as $row) {
		$entries[$row['label']] = $row[$dim];
	}
	$sets[] = array('type'=>'line','entries'=>$entries);
}

Response::sendObject(array('sets'=>$sets));

function fillGaps($rows,$days) {
	$filled = array();
	$now = time();
	for ($i=$days; $i >= 0; $i--) {
		$date = DateUtils::addDays($now,$i*-1);
		$key = date('Ymd',$date);
		if (array_key_exists($key,$rows)) {
			$filled[$key] = $rows[$key];
		} else {
			$filled[$key] = array('hits'=>0,'session'=>0,'ips'=>0,'key'=>$key,'label'=>date('j',$date));
		}
	}
	return $filled;
}
?>