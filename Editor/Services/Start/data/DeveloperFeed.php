<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$url = 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=in2isoft';
$data = RemoteDataService::getRemoteData($url,60*30); // 30 minutes

if (!$data->isHasData()) {
	Response::badGateway();
	exit;	
}
$parser = new FeedParser();
$feed = $parser->parseURL($data->getFile());

if (!$feed) {
	Response::badGateway();
	exit;	
}

$writer = new ListWriter();

$writer->startList();

foreach($feed->getItems() as $item) {
	$title = $item->getTitle();
	$title = str_replace('in2isoft: ','',$title);
	$writer->startRow()->
		startCell(array('class'=>'news'))->startLine()->text($title)->endLine()->
		startLine(array('dimmed'=>true,'mini'=>true))->text(Dates::formatFuzzy($item->getPubDate()))->endLine()->
		endCell()->
		endRow();
}
$writer->endList();
?>