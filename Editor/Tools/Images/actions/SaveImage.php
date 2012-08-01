<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

$object = Image::load($data->id);
if ($object) {
	$object->changeGroups($data->groups);
	$object->setTitle($data->title);
	$object->save();
	$object->publish();
}
?>