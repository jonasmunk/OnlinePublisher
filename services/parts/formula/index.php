<?php
require_once '../../../Editor/Include/Public.php';

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

if (!Strings::isBlank($receiverName) || !Strings::isBlank($receiverEmail)) {
	MailService::send($receiverEmail,$receiverName,"New form submission",$body);
}

?>