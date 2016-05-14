<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$design = Imagegroup::load($data->id);
} else {
	$design = new Imagegroup();
}
$design->setTitle($data->title);
$design->save();
$design->publish();
?>