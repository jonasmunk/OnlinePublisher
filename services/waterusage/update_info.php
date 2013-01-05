<?php
require_once('../../Editor/Include/Public.php');

$number = Request::getString('number');
$phone = Request::getString('phone');
$email = Request::getString('email');

if (StringUtils::isBlank($number)) {
	Response::sendObject(array('success'=>false,'message'=>'No number'));
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

WaterusageService::updateEmailOfMeter($meter,$email);

WaterusageService::updatePhoneOfMeter($meter,$phone);

//Response::badRequest(); exit;

Response::sendObject(array('success'=>true));
?>