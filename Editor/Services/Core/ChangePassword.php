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
		Response::sendUnicodeObject(array('success' => true));
		exit;
		
	}
}
Response::sendUnicodeObject(array('success' => false,'message' => 'Det lykkedes desværre ikke at ændre kodeordet'));
?>