<?php
require_once '../../../Editor/Include/Public.php';

$message = Request::getString('message');
$url = Request::getString('url');
$file = Request::getString('file');
$line = Request::getString('line');

if (Strings::isBlank($message) && Strings::isBlank($url) && Strings::isBlank($file) && Strings::isBlank($line)) {
	exit;
}

$note = $message."\n\nFile: ".$file."\nLine: ".$line."\n\nURL: ".$url."\n\nAgent: ".$_SERVER['HTTP_USER_AGENT'];

$issue = new Issue();
$issue->setTitle($message);
$issue->setNote($note);
$issue->setKind(Issue::$error);
$issue->save();
$issue->publish();
?>