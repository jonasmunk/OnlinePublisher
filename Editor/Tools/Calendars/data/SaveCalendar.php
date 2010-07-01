<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Calendar.php';

$data = Request::getObject('data');

if ($data->id) {
	$src = Calendar::load($data->id);
} else {
	$src = new Calendar();
}
if ($src) {
	$src->setTitle(Request::fromUnicode($data->title));
	$src->save();
	$src->publish();
}
?>