<?php
/**
 * @package OnlinePublisher
 * @subpackage Tool.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/MailService.php';
require_once '../../Classes/Settings.php';
require_once '../../Classes/GoogleAnalytics.php';
require_once '../../Classes/In2iGui.php';

$settings = array(
	'email'=>array(
		'enabled' => MailService::getEnabled(),
		'server' => MailService::getServer(),
		'port' => MailService::getPort(),
		'username' => MailService::getUsername(),
		'password' => MailService::getPassword(),
		'standardEmail' => MailService::getStandardEmail(),
		'standardName' => MailService::getStandardName(),
		'feedbackEmail' => MailService::getFeedbackEmail(),
		'feedbackName' => MailService::getFeedbackName()
	),
	'onlineobjects' => array(
		'url' => Settings::getOnlineObjectsUrl()
	),
	'analytics' => array(
		'username' => GoogleAnalytics::getUsername(),
		'password' => GoogleAnalytics::getPassword(),
		'profile' => GoogleAnalytics::getProfile(),
		'webProfile' => GoogleAnalytics::getWebProfile()
	)
);

In2iGui::sendObject($settings);
?>