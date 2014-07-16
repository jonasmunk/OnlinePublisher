<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$offer = Productoffer::load($data->id);
} else {
	$offer = new Productoffer();
}
$offer->setOffer($data->offer);
$offer->setNote($data->note);
$offer->setExpiry($data->expiry);
$offer->save();
$offer->publish();
?>