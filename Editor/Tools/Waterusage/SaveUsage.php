<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/Waterusage.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$obj = Waterusage::load($data->id);
} else {
	$obj = new Waterusage();
}
$obj->setNumber(Request::fromUnicode($data->number));
$obj->setYear($data->year);
$obj->setValue($data->value);
$obj->setDate($data->date);
$obj->save();
$obj->publish();
?>