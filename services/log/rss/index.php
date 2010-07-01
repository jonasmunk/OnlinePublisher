<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Log
 */

require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Functions.php';
require_once '../../../Editor/Classes/Feed.php';


$feed = new Feed();
$feed->setTitle('OnlinePublisher log');
$feed->setDescription('OnlinePublisher log');
$feed->setPubDate(gmmktime());
$feed->setLastBuildDate(gmmktime());
$feed->setLink($baseUrl);

$sql = "select log.*,UNIX_TIMESTAMP(log.time) as timestamp,object.title as user from log left join object on object.id=log.user_id order by log.time desc limit 50";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$item = new FeedItem();
	$item->setTitle($row['event'].': '.$row['user']);
	$item->setDescription('USER: '.$row['user'].'<br/>IP: '.$row['ip']."<br/>SESSION: ".$row['session']);
	$item->setPubDate($row['timestamp']);
	$item->setGuid($baseUrl.$row['id']);
	$feed->addItem($item);
}
Database::free($result);


$serializer = new FeedSerializer();
$serializer->sendHeaders();
echo $serializer->serialize($feed);
?>