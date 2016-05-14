<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$obj = Waterusage::load($data->id);
} else {
	$obj = new Waterusage();
}
$obj->setWatermeterId($data->meterId);
$obj->setValue($data->value);
$obj->setDate($data->date);
$obj->save();
$obj->publish();
?>