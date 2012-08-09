<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$obj = Image::load($id);
if ($obj) {
	$obj->remove();
}
Response::sendUnicodeObject(array('success'=>true));
?>