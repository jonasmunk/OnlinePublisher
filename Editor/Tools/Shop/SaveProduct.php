<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Product.php';

$data = Request::getObject('data');
if (intval($data->product->id)>0) {
	$product = Product::load($data->product->id);
} else {
	$product = new Product();
}
$product->setTitle(Request::fromUnicode($data->product->title));
$product->setNote(Request::fromUnicode($data->product->note));
$product->setImageId($data->product->imageId ? $data->product->imageId : 0);
$product->setNumber(Request::fromUnicode($data->product->number));
$product->setProductTypeId($data->product->productTypeId);
$product->setAllowOffer($data->product->allowOffer);

$product->save();

$product->updateGroupIds($data->groups);

$attributes = array();
foreach ($data->attributes as $attribute) {
	$attributes[] = array(
		'name'=>Request::fromUnicode($attribute->name),
		'value'=>Request::fromUnicode($attribute->value)
	);
}
$product->updateAttributes($attributes);

$prices = array();
foreach ($data->prices as $price) {
	$prices[] = array(
		'amount'=>Request::fromUnicode($price->amount),
		'type'=>Request::fromUnicode($price->type),
		'price'=>Request::fromUnicode($price->price),
		'currency'=>Request::fromUnicode($price->currency)
	);
}
$product->updatePrices($prices);

$product->publish();
?>