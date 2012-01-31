<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$old = Request::getEncodedString('old');
$password = Request::getEncodedString('password');

if (StringUtils::isBlank($old) || StringUtils::isBlank($password)) {
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