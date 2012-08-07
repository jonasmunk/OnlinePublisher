<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$offer = ProductOffer::load($data->id);
} else {
	$offer = new ProductOffer();
}
$offer->setOffer($data->offer);
$offer->setNote($data->note);
$offer->setExpiry($data->expiry);
$offer->save();
$offer->publish();
?>