<?php
require_once('../../Editor/Include/Public.php');

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