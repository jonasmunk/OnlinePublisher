<?php
require_once '../../../Editor/Include/Public.php';

$requestSecret = Request::getString('secret');
$secret = SettingService::getSharedSecret();

if (StringUtils::isBlank($secret) || StringUtils::isBlank($requestSecret) || $requestSecret!==$secret) {
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
$serializer->sendHeaders();
echo $serializer->serialize($feed);
?>