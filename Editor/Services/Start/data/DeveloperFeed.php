<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$url = 'http://twitter.com/statuses/user_timeline/16827706.rss';
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
		startCell(array('class'=>'news'))->startLine()->text(StringUtils::fromUnicode($title))->endLine()->
		startLine(array('dimmed'=>true,'mini'=>true))->text(DateUtils::formatFuzzy($item->getPubDate()))->endLine()->
		endCell()->
		endRow();
}
$writer->endList();
?>