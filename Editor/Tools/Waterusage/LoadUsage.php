<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';

$data = Request::getObject('data');

$usage = Waterusage::load($data->id);

$meter = Watermeter::load($usage->getWatermeterId());


In2iGui::sendObject(array(
	'id' => $usage->getId(),
	'number' => $meter->getNumber(),
	'value' => $usage->getValue(),
	'date' => $usage->getDate()
));
?>