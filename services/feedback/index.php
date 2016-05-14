<?php
require_once '../../Editor/Include/Public.php';

$name = Request::getString('name');
$email = Request::getString('email');
$message = Request::getString('message');

$feedback = new Feedback();
$feedback->setName($name);
$feedback->setEmail($email);
$feedback->setMessage($message);
$feedback->save();

$body = "Besked fra ".$name." (".$email.")\n\n".$message;
MailService::sendToFeedback("Feedback fra website (".$name.")",$body);
?>