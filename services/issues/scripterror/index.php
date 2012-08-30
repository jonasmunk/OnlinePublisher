<?php
require_once '../../../Editor/Include/Public.php';

$message = Request::getString('message');
$url = Request::getString('url');
$file = Request::getString('file');
$line = Request::getString('line');

$note = $message."\n\nFile: ".$file."\nLine: ".$line."\n\nURL: ".$url;

$issue = new Issue();
$issue->setTitle($message);
$issue->setNote($note);
$issue->setKind(Issue::$error);
$issue->save();
$issue->publish();
?>