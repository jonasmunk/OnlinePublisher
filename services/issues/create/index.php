<?php
require_once '../../../Editor/Include/Public.php';

$user = Request::getString('user');
$site = Request::getString('site');
$description = Request::getString('description');

if (StringUtils::isBlank($user)) {
	Log::debug('No user!');
	exit;
}
if (StringUtils::isBlank($site)) {
	Log::debug('No site!');
	exit;
}
if (StringUtils::isBlank($description)) {
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