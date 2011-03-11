<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/Mailinglist.php';
require_once '../../Classes/In2iGui.php';

$data = Request::getObject('data');
$list = Mailinglist::load($data->id);
$mails = $list->getEmails();
foreach ($mails as $mail) {
	$mail->toUnicode();
}
In2iGui::sendObject($mails);
?>