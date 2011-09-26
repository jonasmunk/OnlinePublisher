<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Producttype.php';

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