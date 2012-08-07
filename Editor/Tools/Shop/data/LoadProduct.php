<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');
$product = Product::load($data->id);

Response::sendUnicodeObject(array(
	'product' => $product,
	'attributes' => $product->getAttributes(),
	'prices' => $product->getPrices(),
	'groups' => $product->getGroupIds()
));
?>