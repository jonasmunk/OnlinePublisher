<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Model/Object.php';

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