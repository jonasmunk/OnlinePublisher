<?php
require_once('inc.php');

usleep(rand(10000,2000000));

Response::sendObject(array(
	'config' => buildConfig(array(
		'baseUrl' => Request::getString('baseUrl'),
		'databaseHost' => Request::getString('databaseHost'),
		'databaseUser' => Request::getString('databaseUser'),
		'databasePassword' => Request::getString('databasePassword'),
		'database' => Request::getString('databaseName'),
		'superUser' => Request::getString('superUser'),
		'superPassword' => Request::getString('superPassword')
	))
));
?>