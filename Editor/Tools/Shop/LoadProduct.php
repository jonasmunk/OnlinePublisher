<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Product.php';

$data = Request::getObject('data');
$product=Product::load($data->id);
$product->toUnicode();

$attributes = $product->getAttributes();
foreach ($attributes as $attribute) {
	$attribute['name'] = mb_convert_encoding($attribute['name'], "UTF-8","ISO-8859-1");
	$attribute['value'] = mb_convert_encoding($attribute['value'], "UTF-8","ISO-8859-1");
}

$prices = $product->getPrices();
foreach ($prices as $price) {
	$price['amount'] = mb_convert_encoding($price['amount'], "UTF-8","ISO-8859-1");
	$price['type'] = mb_convert_encoding($price['type'], "UTF-8","ISO-8859-1");
	$price['price'] = mb_convert_encoding($price['price'], "UTF-8","ISO-8859-1");
	$price['currency'] = mb_convert_encoding($price['currency'], "UTF-8","ISO-8859-1");
}

$groups = $product->getGroupIds();

In2iGui::sendObject(array('product' => $product, 'attributes' => $attributes, 'prices' => $prices, 'groups' => $groups));
?>