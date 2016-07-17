<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id) {
	$stream = Stream::load($data->id);
} else {
	$stream = new Stream();
}
if ($stream) {
	$stream->setTitle($data->title);
	$stream->save();
	$stream->publish();
}
?>