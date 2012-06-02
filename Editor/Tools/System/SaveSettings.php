<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Services/MailService.php';
require_once '../../Classes/Services/SettingService.php';
require_once '../../Classes/Integration/GoogleAnalytics.php';

$data = Request::getObject('data');

if ($data->ui) {
	SettingService::setSetting('part','richtext','experimetal',$data->ui->experimentalRichText);
	SettingService::setSetting('system','security','sharedsecret',$data->ui->sharedSecret);
}
if ($data->onlineobjects) {
	SettingService::setOnlineObjectsUrl($data->onlineobjects->url);
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