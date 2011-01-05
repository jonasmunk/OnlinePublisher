<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Waterusage.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$obj = WaterUsage::load($data->id);
} else {
	$obj = new WaterUsage();
}
$obj->setNumber(Request::fromUnicode($data->number));
$obj->setYear($data->year);
$obj->setValue($data->value);
$obj->setDate($data->date);
error_log(print_r($data->date,true));
error_log(print_r($obj,true));
$obj->save();
$obj->publish();
?>