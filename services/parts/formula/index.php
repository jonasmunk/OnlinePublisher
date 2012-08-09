<?php
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once('../../../Editor/Classes/Core/Request.php');
require_once('../../../Editor/Classes/Core/Response.php');
require_once('../../../Editor/Classes/Parts/FormulaPart.php');
require_once('../../../Editor/Classes/Services/MailService.php');
require_once('../../../Editor/Classes/Core/Log.php');
require_once('../../../Editor/Classes/Objects/Issue.php');
require_once('../../../Editor/Classes/Utilities/StringUtils.php');

$data = Request::getObject('data');

$part = FormulaPart::load($data->id);
if (!$part) {
	Log::debug($data);
	Response::badRequest();
	exit;
}

$body = '';

foreach ($data->fields as $field) {
	$body.= $field->label.": ".$field->value."\n\n";
}

$issue = new Issue();
$issue->setTitle('Form submission');
$issue->setNote($body);
$issue->setKind(Issue::$feedback);
$issue->save();
$issue->publish();


$receiverName = $part->getReceiverName();
$receiverEmail = $part->getReceiverEmail();

if (!StringUtils::isBlank($receiverName) || !StringUtils::isBlank($receiverEmail)) {
	MailService::send($receiverEmail,$receiverName,"New form submission",$body);
}

?>