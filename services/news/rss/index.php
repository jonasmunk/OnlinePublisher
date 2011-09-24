<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Log
 */

require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once '../../../Editor/Classes/Database.php';
require_once '../../../Editor/Classes/Network/Feed.php';
require_once '../../../Editor/Classes/Network/FeedItem.php';
require_once '../../../Editor/Classes/Network/FeedSerializer.php';
require_once '../../../Editor/Classes/Request.php';
require_once '../../../Editor/Classes/Response.php';

$group = Request::getInt('group');
if ($group>0) {
	group($group);
} else {
	Response::badRequest();
}


function group($id) {
	global $baseUrl;
	$feed = new Feed();
	$feed->setTitle('Nyheder');
	$feed->setDescription('Nyheder');
	$feed->setPubDate(gmmktime());
	$feed->setLastBuildDate(gmmktime());
	$feed->setLink($baseUrl);


	$sql = "select object.id,object.title,object.note,UNIX_TIMESTAMP(news.startdate) as startdate,object_link.target_type,object_link.target_value from news,newsgroup_news,object left join object_link on object.id = object_link.object_id where object.id=news.object_id and newsgroup_news.news_id = news.object_id and newsgroup_news.newsgroup_id=".Database::int($id)." order by startdate desc,id,object_link.position";
	$result = Database::select($sql);
	$ids[] = array();
	while ($row = Database::next($result)) {
		if (!in_array($row['id'],$ids)) {
			$item = new FeedItem();
			$item->setTitle($row['title']);
			$item->setDescription($row['note']);
			if ($row['startdate']) {
				$item->setPubDate($row['startdate']);
			}
			$link = buildLink($row['target_type'],$row['target_value']);
			if ($link) {
				$item->setGuid($link);
				$item->setLink($link);
			} else {
				$item->setGuid($baseUrl.$row['id']);
			}
			$feed->addItem($item);
			$ids[] = $row['id'];
		}
	}
	Database::free($result);

	$serializer = new FeedSerializer();
	$serializer->sendHeaders();
	echo $serializer->serialize($feed);
	
}

function buildLink($type,$value) {
	global $baseUrl;
	if ($type=='page') {
       return $baseUrl.'?id='.$value;
	}
    else if ($type=='file') {
       return $baseUrl.'?file='.$value;
	}
    else if ($type=='url' || $type=='email') {
        return $value;
	} else {
		return null;
	}
}
?>