<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/Imagegroup.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$design = ImageGroup::load($data->id);
} else {
	$design = new ImageGroup();
}
$design->setTitle($data->title);
$design->save();
$design->publish();
?>