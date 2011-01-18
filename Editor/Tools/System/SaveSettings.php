<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Services/MailService.php';
require_once '../../Classes/Settings.php';
require_once '../../Classes/GoogleAnalytics.php';

$data = Request::getObject('data');

if ($data->onlineobjects) {
	Settings::setOnlineObjectsUrl($data->onlineobjects->url);
}
if ($data->email) {
	MailService::setServer($data->email->server);
	MailService::setPort($data->email->port);
	MailService::setUsername($data->email->username);
	MailService::setPassword($data->email->password);
	MailService::setStandardEmail($data->email->standardEmail);
	MailService::setStandardName($data->email->standardName);	
	MailService::setFeedbackEmail($data->email->feedbackEmail);
	MailService::setFeedbackName($data->email->feedbackName);	
}
if ($data->analytics) {
	GoogleAnalytics::setUsername($data->analytics->username);
	GoogleAnalytics::setPassword($data->analytics->password);
	GoogleAnalytics::setProfile($data->analytics->profile);
	GoogleAnalytics::setWebProfile($data->analytics->webProfile);
}
?>