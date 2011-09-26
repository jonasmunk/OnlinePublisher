<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Utilities/DateUtils.php';
require_once '../../../Classes/Objects/Event.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Interface/In2iGui.php';

$calendarId = Request::getInt('calendarId');
if ($calendarId>0) {
	listEvents($calendarId,$force);
}

$events = Event::search($query);

function listEvents($id,$force) {

	$query = array('calendarId'=>$id);
	$events = Event::search($query);

	$writer = new ListWriter();

	$writer->startList();
	//$writer->sort($sort,$direction);
	//$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Titel','width'=>40));
	$writer->header(array('title'=>'Lokation'));
	$writer->header(array('title'=>'Start'));
	$writer->header(array('title'=>'Slut'));
	$writer->endHeaders();

	foreach ($events as $event) {
		$writer->startRow(array('kind'=>'event','id'=>$event->getId()));
		$writer->startCell()->text($event->getTitle())->endCell();
		$writer->startCell()->text($event->getLocation())->endCell();
		$writer->startCell()->text(DateUtils::formatLongDateTime($event->getStartdate()))->endCell();
		$writer->startCell()->text(DateUtils::formatLongDateTime($event->getEnddate()))->endCell();
		$writer->endRow();
	}
	$writer->endList();
}