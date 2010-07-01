<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Feedback
*/
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/EmailUtil.php';
require_once '../../Classes/SystemInfo.php';

EmailUtil::send(SystemInfo::getFeedbackMail(),SystemInfo::getFeedbackName(), "OnlinePublisher feedback", requestPostText("message"));

header("Location: index.php?feedbackSent=true");
?>