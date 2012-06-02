<?php
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once('../../../Editor/Classes/Core/Request.php');
require_once('../../../Editor/Classes/Core/Response.php');
require_once('../../../Editor/Classes/Core/Query.php');
require_once('../../../Editor/Classes/Objects/Issue.php');
require_once '../../../Editor/Classes/Network/Feed.php';
require_once '../../../Editor/Classes/Network/FeedItem.php';
require_once '../../../Editor/Classes/Network/FeedSerializer.php';
require_once('../../../Editor/Classes/Utilities/StringUtils.php');



$list = Query::after('issue')->get();


$feed = new Feed();
$feed->setTitle('Nyheder');
$feed->setDescription('Nyheder');
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