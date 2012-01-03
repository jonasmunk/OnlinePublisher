<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

sleep(2);

$

$success = MailService::sendToFeedback()

if (!$success) {
	In2iGui::respondFailure();
}
?>