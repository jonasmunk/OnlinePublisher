<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id) {
	$src = Calendarsource::load($data->id);
} else {
	$src = new Calendarsource();
}
if ($src) {
	$src->setTitle($data->title);
	$src->setDisplayTitle($data->displayTitle);
	$src->setUrl($data->url);
	$src->setFilter($data->filter);
	$src->setSyncInterval($data->syncInterval);
	$src->save();
	$src->publish();
}
?>