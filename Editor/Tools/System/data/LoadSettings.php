<?php
/**
 * @package OnlinePublisher
 * @subpackage Tool.System
 */
require_once '../../../Include/Private.php';

$settings = array(
	'ui' => array(
		'experimentalRichText' => SettingService::getSetting('part','richtext','experimetal') ? true : false,
		'sharedSecret' => SettingService::getSharedSecret()
	),
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
		'url' => SettingService::getOnlineObjectsUrl()
	),
	'analytics' => array(
		'username' => GoogleAnalytics::getUsername(),
		'password' => GoogleAnalytics::getPassword(),
		'profile' => GoogleAnalytics::getProfile(),
		'webProfile' => GoogleAnalytics::getWebProfile()
	),
	'reports' => array(
		'email' => ReportService::getEmail()
	)
);

Response::sendObject($settings);
?>