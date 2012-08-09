<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Watermeter.php';

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