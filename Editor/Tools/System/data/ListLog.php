<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$page = Request::getInt('windowPage');
$showIpSession = Request::getBoolean('showIpSession');
$size = 40;

$result = LogService::getEntries(array(
	'page' => $page,
	'size' => $size,
	'category' => Request::getString('category'),
	'event' => Request::getString('event')
));

$writer = new ListWriter();

$writer->startList()->
	sort('time','descending')->
	window(array( 'total' => $result->getTotal(), 'size' => $size, 'page' => $page ))->
	startHeaders()->
		header(array('title'=>array('Time','da'=>'Tidspunkt'),'key'=>'time'))->
		header(array('title'=>array('Category','da'=>'Kategori')))->
		header(array('title'=>array('Event','da'=>'Begivenhed')))->
		header(array('title'=>array('Entity','da'=>'Entitet')))->
		header(array('title'=>array('Message','da'=>'Besked')))->
		header(array('title'=>array('User','da'=>'Bruger')));
	if ($showIpSession) {		
		$writer->header(array('title'=>'IP'));
		$writer->header(array('title'=>'Session'));
	}
$writer->endHeaders();

foreach ($result->getList() as $row) {
	$writer->startRow(array('kind'=>'logEntry','id'=>$row['id']));
	$writer->startCell()->text(Dates::formatLongDateTime($row['time']))->endCell();
	$writer->startCell()->text($row['category'])->endCell();
	$writer->startCell()->text($row['event'])->endCell();
	$writer->startCell()->text($row['entity'])->endCell();
	$writer->startCell()->text($row['message'])->endCell();
	$writer->startCell()->text($row['username'])->endCell();
	if ($showIpSession) {
		$writer->startCell()->text($row['ip'])->endCell();
		$writer->startCell()->text($row['session'])->endCell();		
	}
	$writer->endRow();
}
$writer->endList();
?>