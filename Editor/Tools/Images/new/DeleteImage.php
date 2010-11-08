<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Image.php';
require_once '../../../Classes/In2iGui.php';

$id = Request::getInt('id');
$obj = Image::load($id);
if ($obj) {
	$obj->remove();
}
In2iGui::sendObject(array('success'=>true));
?>