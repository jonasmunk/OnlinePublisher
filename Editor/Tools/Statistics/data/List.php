<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');
$time = Request::getString('time');

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

?>