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
			Response::sendObject(array('success' => false,'message' => 'Brugeren har ikke adgang'));
			exit;
		} else if (!StringUtils::isBlank($user->getEmail())) {
			if (AuthenticationService::createValidationSession($user)) {
				Response::sendObject(array('success' => true));
				exit;
			} else {
				Response::sendObject(array('success' => false,'message' => 'Det lykkedes ikke at sende e-mail'));
				exit;
			}
		} else {
			Response::sendObject(array('success' => false,'message' => 'Brugeren har ingen e-mail'));
			exit;
		}
	} else {
		Response::sendObject(array('success' => false,'message' => 'Brugeren blev ikke fundet'));
		exit;
	}
	
} else {
	Response::sendObject(array('success' => false,'message' => 'Invalid request'));
}
exit;
?>