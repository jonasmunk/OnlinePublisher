<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../Include/Private.php';

$id = Request::getInt('id');
if (!$id) {
	$data = Request::getObject('data');
	$id = $data->id;
}
$obj=Object::load($id);
if ($obj) {
	$obj->remove();
} else {
	Response::badRequest();
}
?>