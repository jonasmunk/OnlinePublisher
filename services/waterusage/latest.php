<?
require_once('../../Config/Setup.php');
require_once('../../Editor/Include/Public.php');
require_once('../../Editor/Classes/Core/Request.php');
require_once('../../Editor/Classes/Core/Response.php');
require_once('../../Editor/Classes/Core/Query.php');
require_once('../../Editor/Classes/Services/WaterusageService.php');
require_once('../../Editor/Classes/Utilities/StringUtils.php');

///sleep(1);

$number = Request::getString('number');

$usage = WaterusageService::getLatestUsage($number);
if ($usage) {
	Response::sendObject(array(
		'found' => true,
		'value' => $usage->getValue(),
		'date' => $usage->getDate()
	));
} else {
	Response::sendObject(array(
		'found' => false
	));
}

?>