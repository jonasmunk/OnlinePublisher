<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
if ($frame = Frame::load($id)) {
	$object = array(
		'frame' => $frame,
		'topLinks' => FrameService::getLinks($frame,'top'),
		'bottomLinks' => FrameService::getLinks($frame,'bottom'),
		'canRemove' => FrameService::canRemove($frame),
		'newsBlocks' => FrameService::getNewsBlock($frame)
	);
	
	
	Response::sendUnicodeObject($object);
} else {
	Response::notFound();
}
?>