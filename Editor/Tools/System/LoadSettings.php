<?php
/**
 * @package OnlinePublisher
 * @subpackage Tool.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/EmailUtil.php';
require_once '../../Classes/Settings.php';
require_once '../../Classes/GoogleAnalytics.php';
require_once '../../Classes/In2iGui.php';

$settings = array(
	'email'=>array(
		'server' => EmailUtil::getServer(),
		'port' => EmailUtil::getPort(),
		'username' => EmailUtil::getUsername(),
		'password' => EmailUtil::getPassword(),
		'standardEmail' => EmailUtil::getStandardEmail(),
		'standardName' => EmailUtil::getStandardName(),
		'feedbackEmail' => EmailUtil::getFeedbackEmail(),
		'feedbackName' => EmailUtil::getFeedbackName()
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