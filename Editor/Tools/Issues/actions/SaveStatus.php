<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id > 0) {
	$object = Issuestatus::load($data->id);
} else {
	$object = new Issuestatus();
}
$object->setTitle($data->title);
$object->save();
$object->publish();
?>