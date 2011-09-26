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
	$text=Request::getString('text');
	if ($user = AuthenticationService::getUserByEmailOrUsername($text)) {
		if (!$user->getInternal()) {
			In2iGui::sendObject(array('success' => false,'message' => 'Brugeren har ikke adgang'));
			exit;
		} else if (!StringUtils::isBlank($user->getEmail())) {
			if (AuthenticationService::createValidationSession($user)) {
				In2iGui::sendObject(array('success' => true));
				exit;
			} else {
				In2iGui::sendObject(array('success' => false,'message' => 'Det lykkedes ikke at sende e-mail'));
				exit;
			}
		} else {
			In2iGui::sendObject(array('success' => false,'message' => 'Brugeren har ingen e-mail'));
			exit;
		}
	} else {
		In2iGui::sendObject(array('success' => false,'message' => 'Brugeren blev ikke fundet'));
		exit;
	}
} else {
	In2iGui::sendObject(array('success' => false,'message' => 'Invalid request'));
}
exit;
?>