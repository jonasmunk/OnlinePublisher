<?php
require_once '../../Editor/Include/Public.php';

StatisticsService::registerPage([
	'id' => Request::getInt('page'),
	'referrer' => Request::getString('referrer'),
	'uri' => Request::getString('uri')
]);
?>