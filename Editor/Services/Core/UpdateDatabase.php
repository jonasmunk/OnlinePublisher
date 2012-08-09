<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../Include/Public.php';

$username = Request::getString('username');
$password = Request::getString('password');

if (!AuthenticationService::isSuperUser($username,$password)) {
	Response::forbidden();
	exit;	
}

$log = DatabaseUtil::update();

Response::sendObject(array(
	'log' => join("\n",$log),
	'updated' => DatabaseUtil::isUpToDate()
));
?>