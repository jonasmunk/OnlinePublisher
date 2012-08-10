<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$obj = Watermeter::load($data->id);
} else {
	$obj = new Watermeter();
}
$obj->setNumber($data->number);
$obj->save();
$obj->publish();
?>