<?php
$basePath = substr(dirname(__file__), 0, -14);
date_default_timezone_set('Europe/Copenhagen');
require_once($basePath."Config/Setup.php");
require_once($basePath."Editor/Include/Classloader.php");

if (!AuthenticationService::isInternalUser(Request::getString('username'),Request::getString('password'))) {
	Response::forbidden();
	exit;
}
?>