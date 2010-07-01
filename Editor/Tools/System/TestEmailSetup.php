<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/EmailUtil.php';

$data = Request::getObject('data');
if (EmailUtil::send($data->email,$data->name,Request::fromUnicode($data->subject),Request::fromUnicode($data->body))) {
	error_log('Success!');
} else {
	error_log('Failure!');
}
?>