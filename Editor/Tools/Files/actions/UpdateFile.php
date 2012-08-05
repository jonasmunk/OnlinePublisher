<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

$file = File::load($data->id);
if ($file) {
	$file->updateGroupIds($data->groups);
	$file->setTitle($data->title);
	$file->save();
	$file->publish();
}
?>