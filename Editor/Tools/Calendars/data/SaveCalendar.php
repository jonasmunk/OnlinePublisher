<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Objects/Calendar.php';

$data = Request::getUnicodeObject('data');

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