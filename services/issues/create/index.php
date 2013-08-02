<?php
require_once '../../../Editor/Include/Public.php';

$user = Request::getString('user');
$site = Request::getString('site');
$description = Request::getString('description');

if (Strings::isBlank($user)) {
	Log::debug('No user!');
	exit;
}
if (Strings::isBlank($site)) {
	Log::debug('No site!');
	exit;
}
if (Strings::isBlank($description)) {
	Log::debug('No description!');
	exit;
}

$issue = new Issue();
$issue->setTitle($user.' from '.$site);
$issue->setNote($description);
$issue->setKind(Issue::$feedback);
$issue->save();
$issue->publish();

Log::debug($issue);
?>