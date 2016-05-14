<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../Include/Private.php';

$id = Request::getInt('id');
if (!$id) {
	$data = Request::getObject('data');
	$id = $data->id;
}
if (!$id) {
	Response::badRequest();
} else {
	$obj = Object::load($id);
	if (!$obj) {
		Response::notFound('Unable to load object with id='.$id);
	} else {
		Response::sendObject($obj);
	}
}
?>