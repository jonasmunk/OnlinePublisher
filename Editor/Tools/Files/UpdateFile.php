<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/File.php';

$data = Request::getUnicodeObject('data');

$file = File::load($data->id);
if ($file) {
	$file->updateGroupIds($data->groups);
	$file->setTitle($data->title);
	$file->save();
	$file->publish();
}
?>