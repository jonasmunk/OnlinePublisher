<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Image.php';
require_once '../../Classes/Interface/In2iGui.php';

$id = Request::getInt('id');
$obj = Image::load($id);
if ($obj) {
	$obj->remove();
}
In2iGui::sendObject(array('success'=>true));
?>