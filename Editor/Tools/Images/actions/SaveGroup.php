<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

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