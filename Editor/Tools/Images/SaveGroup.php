<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/Imagegroup.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$design = ImageGroup::load($data->id);
} else {
	$design = new ImageGroup();
}
$design->setTitle(Request::fromUnicode($data->title));
$design->save();
$design->publish();
?>