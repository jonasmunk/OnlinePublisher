<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Calendarsource.php';

$id = Request::getInt('id');

$src = CalendarSource::load($id);
if ($src) {
	$src->synchronize(true);
}
?>