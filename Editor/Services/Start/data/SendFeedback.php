<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$message = Request::getString('message');

$success = MailService::sendToFeedback('Feedback',$message);

if (!$success) {
	In2iGui::respondFailure();
}
?>