<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$obj = Event::load($id);
if ($obj) {
	$obj->remove();
}
?>