<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Objects/Event.php';

$data = Request::getUnicodeObject('data');

if ($data->id) {
	$src = Event::load($data->id);
} else {
	$src = new Event();
}
if ($src) {
	$src->setTitle($data->title);
	$src->setLocation($data->location);
	$src->setStartdate($data->startdate);
	$src->setEnddate($data->enddate);
	$src->save();
	$src->publish();
	$src->updateCalendarIds($data->calendars);
}
?>