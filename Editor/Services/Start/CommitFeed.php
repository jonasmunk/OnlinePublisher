<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Network/FeedParser.php';
require_once '../../Classes/Utilities/DateUtils.php';
require_once '../../Classes/Services/RemoteDataService.php';

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

$writer = new ArticlesWriter();

$writer->startArticles();

foreach($feed->getItems() as $item) {
	$title = $item->getTitle();
	if (StringUtils::startsWith($title,'Merge branch')) {
		continue;
	}
	$writer->startArticle();
	$writer->startTitle()->text($title)->endTitle();
	$writer->startParagraph(array('dimmed'=>true))->text(DateUtils::formatFuzzy($item->getPubDate()))->endParagraph();
	$writer->endArticle();
	
}
$writer->endArticles();
?>