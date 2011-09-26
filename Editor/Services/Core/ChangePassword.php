<?php
/**
 * @package OnlinePublisher
 * @subpackage Services
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Public.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Services/AuthenticationService.php';
require_once '../../Classes/Utilities/StringUtils.php';

if (Request::isPost()) {
	$key = Request::getString('key');
	$password = Request::getString('password');
	if (AuthenticationService::updatePasswordForEmailValidationSession($key,$password)) {
		In2iGui::sendObject(array('success' => true));
		exit;
		
	}
}
In2iGui::sendObject(array('success' => false,'message' => 'Det lykkedes desværre ikke at ændre kodeordet'));
?>