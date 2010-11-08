<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Image.php';

$data = Request::getObject('data');

$object = Image::load($data->id);
if ($object) {
	$object->changeGroups($data->groups);
	$object->setTitle(Request::fromUnicode($data->title));
	$object->save();
	$object->publish();
}
?>