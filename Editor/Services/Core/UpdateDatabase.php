<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Public.php';
require_once '../../Classes/Utilities/DatabaseUtil.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';
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