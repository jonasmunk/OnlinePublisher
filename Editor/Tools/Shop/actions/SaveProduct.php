<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');
if (intval($data->product->id)>0) {
	$product = Product::load($data->product->id);
} else {
	$product = new Product();
}
$product->setTitle($data->product->title);
$product->setNote($data->product->note);
$product->setImageId($data->product->imageId ? $data->product->imageId : 0);
$product->setNumber($data->product->number);
$product->setProductTypeId($data->product->productTypeId);
$product->setAllowOffer($data->product->allowOffer);

$product->save();

$product->updateGroupIds($data->groups);

$attributes = array();
foreach ($data->attributes as $attribute) {
	$attributes[] = array(
		'name'=>$attribute->name,
		'value'=>$attribute->value
	);
}
$product->updateAttributes($attributes);

$prices = array();
foreach ($data->prices as $price) {
	$prices[] = array(
		'amount'=>$price->amount,
		'type'=>$price->type,
		'price'=>$price->price,
		'currency'=>$price->currency
	);
}
$product->updatePrices($prices);

$product->publish();
?>