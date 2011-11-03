<?
require_once('../../Config/Setup.php');
require_once('../../Editor/Include/Public.php');
require_once('../../Editor/Classes/Core/Request.php');
require_once('../../Editor/Classes/Core/Response.php');
require_once('../../Editor/Classes/Core/Query.php');
require_once('../../Editor/Classes/Objects/Waterusage.php');
require_once('../../Editor/Classes/Services/WaterusageService.php');
require_once('../../Editor/Classes/Utilities/DateUtils.php');
require_once('../../Editor/Classes/Utilities/StringUtils.php');

$number = Request::getString('number');
$date = Request::getString('date');
$value = Request::getInt('value',null);
$date = DateUtils::parse($date);

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
	$meter = new Watermeter();
	$meter->setNumber($number);
	$meter->save();
	$meter->publish();
}

$usage = new Waterusage();
$usage->setWatermeterId($meter->getId());
$usage->setValue($value);
$usage->setDate($date);
$usage->save();
$usage->publish();

Response::sendObject(array('success'=>true));
?>