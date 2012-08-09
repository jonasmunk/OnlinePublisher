<?php
/**
 * @package OnlinePublisher
 * @subpackage Services
 */
require_once '../../Include/Public.php';

if (Request::isPost()) {
	
	$text = Request::getString('text');
	if ($user = AuthenticationService::getUserByEmailOrUsername($text)) {
		if (!$user->getInternal()) {
			Response::sendUnicodeObject(array('success' => false,'message' => 'Brugeren har ikke adgang'));
			exit;
		} else if (!StringUtils::isBlank($user->getEmail())) {
			if (AuthenticationService::createValidationSession($user)) {
				Response::sendUnicodeObject(array('success' => true));
				exit;
			} else {
				Response::sendUnicodeObject(array('success' => false,'message' => 'Det lykkedes ikke at sende e-mail'));
				exit;
			}
		} else {
			Response::sendUnicodeObject(array('success' => false,'message' => 'Brugeren har ingen e-mail'));
			exit;
		}
	} else {
		Response::sendUnicodeObject(array('success' => false,'message' => 'Brugeren blev ikke fundet'));
		exit;
	}
	
} else {
	Response::sendUnicodeObject(array('success' => false,'message' => 'Invalid request'));
}
exit;
?>