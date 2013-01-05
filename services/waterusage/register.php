<?php
require_once('../../Editor/Include/Public.php');

//sleep(1);

$number = Request::getString('number');
$date = Request::getString('date');
$value = Request::getInt('value',null);
$date = DateUtils::parse($date);
$phone = Request::getString('phone');
$email = Request::getString('email');

if (StringUtils::isBlank($number)) {
	Response::sendObject(array('success'=>false,'message'=>'No number'));
	exit;
}
if (!$value) {
	Response::sendObject(array('success'=>false,'message'=>'No value'));
	exit;
}
if (!$date) {
	Response::sendObject(array('success'=>false,'message'=>'No date'));
	exit;
}

$meter = Query::after('watermeter')->withProperty('number',$number)->first();
if (!$meter) {
	Response::sendObject(array('success'=>false,'message'=>'Number not found','key'=>'notfound'));
	exit;
	$meter = new Watermeter();
	$meter->setNumber($number);
	$meter->save();
	$meter->publish();
}

$usage = new Waterusage();
$usage->setWatermeterId($meter->getId());
$usage->setSource(Waterusage::$CLIENT);
$usage->setStatus(Waterusage::$UNKNOWN);
$usage->setValue($value);
$usage->setDate($date);
$usage->save();
$usage->publish();

if (StringUtils::isNotBlank($email)) {
	WaterusageService::updateEmailOfMeter($meter,$email);
}

if (StringUtils::isNotBlank($phone)) {
	WaterusageService::updatePhoneOfMeter($meter,$phone);
}

Response::sendObject(array('success'=>true,'id'=>$usage->getId()));
?>