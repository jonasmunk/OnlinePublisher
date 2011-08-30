<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Public.php';
require_once '../../Classes/DatabaseUtil.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Services/AuthenticationService.php';

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