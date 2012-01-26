<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$old = Request::getEncodedString('old');
$password = Request::getEncodedString('new');

$user = AuthenticationService::getUser('jbm',$old);

if ($user) {
	AuthenticationService::setPassword($user,$password);
	$user->save();
	$user->publish();
} else {
	Response::badRequest();
}
?>