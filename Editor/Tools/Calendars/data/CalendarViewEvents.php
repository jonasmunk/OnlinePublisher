<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/VCal.php';
require_once '../../../Classes/Objects/Calendarsource.php';
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

	$out = array();

	foreach ($events as $event) {
		$out[] = array('startTime'=>$event['startDate'],'endTime'=>$event['endDate'],'text'=>mb_convert_encoding($event['summary'], "UTF-8","ISO-8859-1"));
	}
	In2iGui::sendObject($out);
}