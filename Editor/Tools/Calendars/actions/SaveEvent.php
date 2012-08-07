<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

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