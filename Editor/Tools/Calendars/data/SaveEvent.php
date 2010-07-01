<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Event.php';
require_once '../../../Classes/Log.php';

$data = Request::getObject('data');
Log::debug($data->calendars);

if ($data->id) {
	$src = Event::load($data->id);
} else {
	$src = new Event();
}
if ($src) {
	$src->setTitle(Request::fromUnicode($data->title));
	$src->setLocation(Request::fromUnicode($data->location));
	$src->setStartdate($data->startdate);
	$src->setEnddate($data->enddate);
	$src->save();
	$src->publish();
	$src->updateCalendarIds($data->calendars);
}
?>