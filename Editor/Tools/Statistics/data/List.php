<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');
$time = Request::getString('time');

if ($kind=='live') {
	live();
} else {
	pages();
}

function live() {
	$writer = new ListWriter();


	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('Time','da'=>'Tidspunkt')));
	$writer->header(array('title'=>array('Page','da'=>'Side')));
	$writer->header(array('title'=>array('Session','da'=>'Session')));
	$writer->header(array('title'=>array('Device','da'=>'Maskine')));
	$writer->endHeaders();

	$sql = "select UNIX_TIMESTAMP(statistics.time) as time,statistics.type,statistics.session,statistics.ip,page.title as page_title from statistics left join page on statistics.value=page.id where statistics.type='page' order by statistics.time desc limit 100";
	$result = Database::select($sql);
	while($row = Database::next($result)) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/time'))->text(DateUtils::formatFuzzy($row['time']))->endCell();
		$writer->startCell()->icon('common/page')->text($row['page_title'])->endCell();
		$writer->startCell()->text($row['session'])->endCell();
		$writer->startCell()->text($row['ip'])->endCell();
		$writer->endRow();
	}
	Database::free($result);
	$writer->endList();	
}

function pages() {
	$query = new StatisticsQuery();

	$result = StatisticsService::search($query);

	$writer = new ListWriter();

	$writer->startList();
	if ($result) {
		$writer->startHeaders();
		$writer->header(array('title'=>array('Date','da'=>'Dato')));
		$writer->header(array('title'=>array('Pageviews','da'=>'Sidevisninger')));
		$writer->header(array('title'=>array('Sessioner','da'=>'Sessions')));
		$writer->header(array('title'=>array('Devices','da'=>'Maskiner')));
		$writer->endHeaders();

		foreach ($result as $row) {
			$writer->startRow();
			$writer->startCell(array('icon'=>'common/time'))->text($row['label'])->endCell();
			$writer->startCell()->text($row['hits'])->endCell();
			$writer->startCell()->text($row['sessions'])->endCell();
			$writer->startCell()->text($row['ips'])->endCell();
			$writer->endRow();
		}
	}
	$writer->endList();
}
?>