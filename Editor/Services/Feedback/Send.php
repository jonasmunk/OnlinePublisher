<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Feedback
*/
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Services/MailService.php';
require_once '../../Classes/Core/SystemInfo.php';
require_once '../../Classes/Core/Request.php';

MailService::send(SystemInfo::getFeedbackMail(),SystemInfo::getFeedbackName(), "OnlinePublisher feedback", Request::getString("message"));

header("Location: index.php?feedbackSent=true");
?>