<?php
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';

$list = Query::after('issue')->get();

$feed = new Feed();
$feed->setTitle('Issues');
$feed->setDescription('Issues');
$feed->setPubDate(gmmktime());
$feed->setLastBuildDate(gmmktime());
$feed->setLink($baseUrl);

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