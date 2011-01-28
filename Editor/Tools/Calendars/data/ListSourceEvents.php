<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/VCal.php';
require_once '../../../Classes/Utilities/DateUtils.php';
require_once '../../../Classes/Calendarsource.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/In2iGui.php';

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
	$writer->header(array('title'=>'Titel','width'=>40));
	$writer->header(array('title'=>'Lokation'));
	$writer->header(array('title'=>'Start'));
	$writer->header(array('title'=>'Slut'));
	$writer->header(array('title'=>'Bonus'));
	$writer->endHeaders();

	foreach ($events as $event) {
		$writer->startRow();
		$writer->startCell()->text($event['summary'])->endCell();
		$writer->startCell()->text($event['location'])->endCell();
		$writer->startCell()->text(DateUtils::formatLongDateTime($event['startDate']))->endCell();
		$writer->startCell()->text(DateUtils::formatLongDateTime($event['endDate']))->endCell();
		$writer->startCell()->text($event['recurring'])->endCell();
		$writer->endRow();
	}
	$writer->endList();
}