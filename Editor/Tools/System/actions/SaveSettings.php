<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->ui) {
	SettingService::setSetting('part','richtext','experimetal',$data->ui->experimentalRichText);
	SettingService::setSharedSecret($data->ui->sharedSecret);
}
if ($data->onlineobjects) {
	SettingService::setOnlineObjectsUrl($data->onlineobjects->url);
}
if ($data->reports) {
	ReportService::setEmail($data->reports->email);
}
if ($data->email) {
	MailService::setEnabled($data->email->enabled);
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