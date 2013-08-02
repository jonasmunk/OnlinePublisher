<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../Include/Public.php';

$superUsername = Request::getString('superUsername');
$superPassword = Request::getString('superPassword');

$adminUsername = Request::getString('adminUsername');
$adminPassword = Request::getString('adminPassword');

if (!AuthenticationService::isSuperUser($superUsername,$superPassword)) {
	Response::forbidden();
	exit;	
}
if (Strings::isBlank($adminUsername) || Strings::isBlank($adminPassword)) {
	Response::badRequest();
	exit;
}

$user = new User();
$user->setUsername($adminUsername);
$user->setTitle($adminUsername);
AuthenticationService::setPassword($user,$adminPassword);
$user->setInternal(true);
$user->setAdministrator(true);
$user->create();
$user->publish();
?>