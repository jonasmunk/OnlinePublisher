<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Object.php';

$id = Request::getInt('id');
if (!$id) {
	$data = Request::getObject('data');
	$id = $data->id;
}
if (!$id) {
	Response::badRequest();
} else {
	$obj=Object::load($id);
	if (!$obj) {
		Response::notFound('Unable to load object with id='.$id);
	} else {
		$obj->toUnicode();
		In2iGui::sendObject($obj);
	}
}
?>