<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Producttype.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$type = ProductType::load($data->id);
} else {
	$type = new ProductType();
}
$type->setTitle(Request::fromUnicode($data->title));
$type->setNote(Request::fromUnicode($data->note));
$type->save();
$type->publish();
?>