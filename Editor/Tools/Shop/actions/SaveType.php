<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$type = Producttype::load($data->id);
} else {
	$type = new Producttype();
}
$type->setTitle($data->title);
$type->setNote($data->note);
$type->save();
$type->publish();
?>