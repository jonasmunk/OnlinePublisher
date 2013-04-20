<?php
require_once '../../../Editor/Include/Public.php';

$text = Request::getString('text');
$email = Request::getString('email');
$pageId = Request::getInt('pageId');

if (StringUtils::isBlank($text)) {
	Response::badRequest('No text!');
	exit;
}

$page = Page::load($pageId);

$title = 'Feedback';
if ($page) {
	$title.=' : '.$page->getTitle();
}
if (StringUtils::isNotBlank($email)) {
	$title.=' : '.$email;
}

$issue = new Issue();
$issue->setTitle($title);
$issue->setNote($text);
$issue->setKind(Issue::$feedback);
$issue->save();
$issue->publish();


if ($page) {
	RelationsService::relateObjectToPage($issue,$page,'subject');
}
?>