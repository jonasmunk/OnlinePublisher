<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$src = Source::load(Request::getId());
if ($src) {
	$src->setSynchronized(time());
	$src->save();
	$src->publish();
} else {
  Response::notFound();
}
?>