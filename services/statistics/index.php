<?php
require_once '../../Editor/Include/Public.php';

StatisticsService::registerPage(array(
	'id' => Request::getInt('page'),
	'referer' => Request::getString('referer'),
	'uri' => Request::getString('uri')
));
?>