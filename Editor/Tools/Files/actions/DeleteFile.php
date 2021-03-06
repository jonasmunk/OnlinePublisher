<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$file = File::load($id);
if ($file) {
	$file->remove();
	Response::sendObject(array('success'=>true));
} else {
	Response::notFound();
}
?>