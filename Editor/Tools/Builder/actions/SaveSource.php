<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id) {
	$src = Source::load($data->id);
} else {
	$src = new Source();
}
if ($src) {
	$src->setTitle($data->title);
	$src->setUrl($data->url);
	$src->setInterval($data->interval);
	$src->save();
	$src->publish();
}
?>