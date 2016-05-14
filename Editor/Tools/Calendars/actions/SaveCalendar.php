<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id) {
	$src = Calendar::load($data->id);
} else {
	$src = new Calendar();
}
if ($src) {
	$src->setTitle($data->title);
	$src->save();
	$src->publish();
}
?>