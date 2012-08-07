<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$type = ProductType::load($data->id);
} else {
	$type = new ProductType();
}
$type->setTitle($data->title);
$type->setNote($data->note);
$type->save();
$type->publish();
?>