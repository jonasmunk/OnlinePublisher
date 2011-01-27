<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../Include/Private.php';
require_once '../../Classes/In2iGui.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Tidspunkt'));
$writer->header(array('title'=>'Kategori'));
$writer->header(array('title'=>'Begivenhed'));
$writer->header(array('title'=>'Entitet'));
$writer->header(array('title'=>'Besked'));
$writer->header(array('title'=>'Bruger'));
$writer->header(array('title'=>'IP'));
$writer->header(array('title'=>'Session'));
$writer->endHeaders();

$list = LogService::getEntries();
foreach ($list as $row) {
	$writer->startRow(array('kind'=>'logEntry','id'=>$row['id']));
	$writer->startCell()->text(DateUtils::formatLongDateTime($row['time']))->endCell();
	$writer->startCell()->text($row['category'])->endCell();
	$writer->startCell()->text($row['event'])->endCell();
	$writer->startCell()->text($row['entity'])->endCell();
	$writer->startCell()->text($row['message'])->endCell();
	$writer->startCell()->text($row['username'])->endCell();
	$writer->startCell()->text($row['ip'])->endCell();
	$writer->startCell()->text($row['session'])->endCell();
	$writer->endRow();
}
$writer->endList();
?>