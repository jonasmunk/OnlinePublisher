<?php
require_once '../../../Editor/Include/Public.php';

$secret = Request::getString('secret');

if (!AuthenticationService::isSharedSecret($secret)) {
	Response::forbidden("The secret code was incorrect");
	exit;
}

$list = Query::after('issue')->get();

$feed = new Feed();
$feed->setTitle('Issues');
$feed->setDescription('Issues');
$feed->setPubDate(gmmktime());
$feed->setLastBuildDate(gmmktime());
$feed->setLink(ConfigurationService::getBaseUrl());

foreach ($list as $issue) {
	$item = new FeedItem();
	$item->setTitle($issue->getTitle());
	$item->setDescription($issue->getNote());
	$item->setPubDate($issue->getUpdated());
	$item->setGuid($issue->getId());
	$feed->addItem($item);
}

$serializer = new FeedSerializer();
$serializer->send($feed);
?>