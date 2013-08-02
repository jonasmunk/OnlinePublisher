<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$old = Request::getString('old');
$password = Request::getString('password');

if (Strings::isBlank($old) || Strings::isBlank($password)) {
	Response::badRequest();
}


$user = AuthenticationService::getUser(InternalSession::getUsername(),$old);

if ($user) {
	AuthenticationService::setPassword($user,$password);
	$user->save();
	$user->publish();
} else {
	Response::badRequest();
}
?>