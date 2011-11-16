<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$url = 'http://twitter.com/statuses/user_timeline/16827706.rss';
$data = RemoteDataService::getRemoteData($url,60*30); // 30 minutes

if (!$data->isHasData()) {
	In2iGui::respondFailure();
	exit;	
}
$parser = new FeedParser();
$feed = $parser->parseURL($data->getFile());

if (!$feed) {
	In2iGui::respondFailure();
	exit;	
}

$writer = new ListWriter();

$writer->startList();

foreach($feed->getItems() as $item) {
	$title = $item->getTitle();
	$title = str_replace('in2isoft: ','',$title);
	$writer->startRow()->
		startCell()->startLine()->text($title)->endLine()->
		startLine(array('dimmed'=>true,'mini'=>true))->text(DateUtils::formatFuzzy($item->getPubDate()))->endLine()->
		endCell()->
		endRow();
}
$writer->endList();
?>