<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$url = 'https://github.com/in2isoft/OnlinePublisher/commits/master.atom';
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
	if (StringUtils::startsWith($title,'Merge branch')) {
		continue;
	}
	$writer->startRow()->
		startCell(array('class'=>'news'))->
			startLine()->text($title)->endLine()->
			startLine(array('dimmed'=>true,'mini'=>true))->text(DateUtils::formatFuzzy($item->getPubDate()))->endLine()->
		endCell()->
	endRow();
	
}
$writer->endList();
?>