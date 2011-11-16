<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$status = Request::getInt('status');

if ($obj = Waterusage::load($id)) {
	$obj->setStatus($status);
	$obj->save();
	$obj->publish();
} else {
	Response::badRequest();
}
?>