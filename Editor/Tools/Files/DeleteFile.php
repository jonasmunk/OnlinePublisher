<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/File.php';
require_once '../../Classes/In2iGui.php';

$id = Request::getInt('id');
$file = File::load($id);
if ($file) {
	$file->remove();
}
In2iGui::sendObject(array('success'=>true));
?>