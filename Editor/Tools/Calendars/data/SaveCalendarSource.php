<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Objects/Calendarsource.php';

$data = Request::getObject('data');

if ($data->id) {
	$src = CalendarSource::load($data->id);
} else {
	$src = new CalendarSource();
}
if ($src) {
	$src->setTitle(Request::fromUnicode($data->title));
	$src->setUrl(Request::fromUnicode($data->url));
	$src->setFilter(Request::fromUnicode($data->filter));
	$src->setSyncInterval($data->syncInterval);
	$src->save();
	$src->publish();
}
?>