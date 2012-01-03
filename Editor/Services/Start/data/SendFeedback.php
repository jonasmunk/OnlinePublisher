<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

sleep(2);

$message = Request::getString('message');

$success = MailService::sendToFeedback('Feedback',$message);

if (!$success) {
	In2iGui::respondFailure();
}
?>