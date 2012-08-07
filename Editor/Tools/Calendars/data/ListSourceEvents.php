<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$force = Request::getBoolean('force');
$sourceId = Request::getInt('sourceId');
if ($sourceId>0) {
	listSource($sourceId,$force);
}

function listSource($id,$force) {

	$source = Calendarsource::load($id);

	$source->synchronize($force);

	$query = array('sort' => 'startDate');

	$events = $source->getEvents($query);

	$writer = new ListWriter();

	$writer->startList();
	//$writer->sort($sort,$direction);
	//$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>array('Title','da'=>'Titel'),'width'=>30));
	$writer->header(array('title'=>array('Location','da'=>'Lokation'),'width'=>20));
	$writer->header(array('title'=>'URL'));
	$writer->header(array('title'=>'Start','width'=>1));
	$writer->header(array('title'=>array('End','da'=>'Slut'),'width'=>1));
	$writer->header(array('width'=>1));
	$writer->endHeaders();

	foreach ($events as $event) {
		$writer->startRow()
			->startCell(array('icon'=>'common/time'))->
				startLine()->startWrap()->text($event['summary'])->endWrap()->endLine()->
				startLine(array('dimmed'=>true))->startWrap()->text($event['description'])->endWrap()->endLine()->
			endCell();
		$writer->startCell()->text($event['location'])->endCell();
		$writer->startCell()->startWrap()->text($event['url'])->endWrap()->endCell();
		$writer->startCell(array('wrap'=>false))->text(DateUtils::formatLongDateTime($event['startDate']))->endCell();
		$writer->startCell(array('wrap'=>false))->text(DateUtils::formatLongDateTime($event['endDate']))->endCell();
		$writer->startCell();
		if ($event['recurring']) {
			$writer->icon(array('icon'=>'common/recycle'));
		}
		$writer->endCell();
		$writer->endRow();
	}
	$writer->endList();
}