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
	$obj = Page::load($id);
	if (!$obj) {
		Response::notFound('Unable to load page with id='.$id);
	} else {
		Response::sendObject($obj);
	}
}
?>