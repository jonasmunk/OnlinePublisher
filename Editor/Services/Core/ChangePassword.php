<?php
/**
 * @package OnlinePublisher
 * @subpackage Services
 */
require_once '../../Include/Public.php';

if (Request::isPost()) {
	$key = Request::getString('key');
	$password = Request::getString('password');
	if (AuthenticationService::updatePasswordForEmailValidationSession($key,$password)) {
		Response::sendObject(array('success' => true));
		exit;
		
	}
}
Response::sendObject(array('success' => false,'message' => 'Det lykkedes desværre ikke at ændre kodeordet'));
?>