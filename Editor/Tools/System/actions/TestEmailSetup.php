<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');
if (MailService::send($data->email,$data->name,$data->subject,$data->body)) {
	Response::sendUnicodeObject(array('success'=>true));
} else {
	Response::sendUnicodeObject(array('success'=>false));
}
?>