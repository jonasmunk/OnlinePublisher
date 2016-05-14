<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$design = Filegroup::load($data->id);
} else {
	$design = new Filegroup();
}
$design->setTitle($data->title);
$design->save();
$design->publish();
?>