<?php
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once('../../../Editor/Classes/Core/Request.php');
require_once('../../../Editor/Classes/Core/Response.php');
require_once('../../../Editor/Classes/Parts/FormulaPart.php');
require_once('../../../Editor/Classes/Services/MailService.php');
require_once('../../../Editor/Classes/Core/Log.php');
require_once('../../../Editor/Classes/Utilities/StringUtils.php');

$name = Request::getUnicodeString('name');
$email = Request::getUnicodeString('email');
$message = Request::getUnicodeString('message');
$id = Request::getInt('id');

$part = FormulaPart::load($id);
$receiverName = $part->getReceiverName();
$receiverEmail = $part->getReceiverEmail();

if (StringUtils::isBlank($receiverName) || StringUtils::isBlank($receiverEmail)) {
	Log::debug($part);
	Log::debug('Not set up correctly!');
	Response::badRequest();
	exit;
}

$body = "Besked fra ".$name." (".$email.")\n\n".$message;
MailService::send($receiverEmail,$receiverName,"Besked fra website (".$name.")",$body);
?>