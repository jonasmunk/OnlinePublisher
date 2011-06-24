<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Objects/Calendarsource.php';

$data = Request::getUnicodeObject('data');

if ($data->id) {
	$src = CalendarSource::load($data->id);
} else {
	$src = new CalendarSource();
}
if ($src) {
	$src->setTitle($data->title);
	$src->setUrl($data->url);
	$src->setFilter($data->filter);
	$src->setSyncInterval($data->syncInterval);
	$src->save();
	$src->publish();
}
?>