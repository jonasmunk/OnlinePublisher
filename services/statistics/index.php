<?php
require_once '../../Editor/Include/Public.php';

session_set_cookie_params(0);
session_start();

StatisticsService::registerPage(array(
	'id' => Request::getInt('page'),
	'referrer' => Request::getString('referrer'),
	'uri' => Request::getString('uri')
));
?>