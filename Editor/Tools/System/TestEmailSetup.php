<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Services/MailService.php';
require_once '../../Classes/In2iGui.php';

$data = Request::getObject('data');
if (MailService::send($data->email,$data->name,Request::fromUnicode($data->subject),Request::fromUnicode($data->body))) {
	In2iGui::sendObject(array('success'=>true));
} else {
	In2iGui::sendObject(array('success'=>false));
}
?>