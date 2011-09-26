<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Model/Object.php';

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