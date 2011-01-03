<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/EmailUtil.php';
require_once '../../Classes/Settings.php';
require_once '../../Classes/GoogleAnalytics.php';

$data = Request::getObject('data');

if ($data->onlineobjects) {
	Settings::setOnlineObjectsUrl($data->onlineobjects->url);
}
if ($data->email) {
	EmailUtil::setServer($data->email->server);
	EmailUtil::setPort($data->email->port);
	EmailUtil::setUsername($data->email->username);
	EmailUtil::setPassword($data->email->password);
	EmailUtil::setStandardEmail($data->email->standardEmail);
	EmailUtil::setStandardName($data->email->standardName);	
	EmailUtil::setFeedbackEmail($data->email->feedbackEmail);
	EmailUtil::setFeedbackName($data->email->feedbackName);	
}
if ($data->analytics) {
	GoogleAnalytics::setUsername($data->analytics->username);
	GoogleAnalytics::setPassword($data->analytics->password);
	GoogleAnalytics::setProfile($data->analytics->profile);
	GoogleAnalytics::setWebProfile($data->analytics->webProfile);
}
?>