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

	$out = array();

	foreach ($events as $event) {
		$out[] = array('startTime'=>$event['startDate'],'endTime'=>$event['endDate'],'text'=>$event['summary']);
	}
	Response::sendObject($out);
}