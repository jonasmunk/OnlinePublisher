<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$src = Calendar::load($id);
if ($src) {
	$src->remove();
}
?>