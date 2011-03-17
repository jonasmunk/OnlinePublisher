<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/Watermeter.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$obj = Watermeter::load($data->id);
} else {
	$obj = new Watermeter();
}
$obj->setNumber(Request::fromUnicode($data->number));
$obj->save();
$obj->publish();
?>