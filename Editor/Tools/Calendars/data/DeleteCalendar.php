<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Objects/Calendar.php';

$id = Request::getInt('id');

$src = Calendar::load($id);
if ($src) {
	$src->remove();
}
?>