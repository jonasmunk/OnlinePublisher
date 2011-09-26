<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Image.php';

$data = Request::getUnicodeObject('data');

$object = Image::load($data->id);
if ($object) {
	$object->changeGroups($data->groups);
	$object->setTitle($data->title);
	$object->save();
	$object->publish();
}
?>