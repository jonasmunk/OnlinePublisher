<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$src = Calendarsource::load($id);
if ($src) {
	$src->synchronize(true);
}
?>