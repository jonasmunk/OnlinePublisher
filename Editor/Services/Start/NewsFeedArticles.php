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

$url = 'http://www.in2isoft.dk/services/news/rss/?group=373';

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
	$writer->startArticle();
	$writer->startTitle()->text($item->getTitle())->endTitle();
	$writer->startParagraph()->text($item->getDescription())->endParagraph();
	$writer->startParagraph(array('dimmed'=>true))->text(DateUtils::formatFuzzy($item->getPubDate()))->endParagraph();
	//$writer->startCell()->text()->endCell();
	$writer->endArticle();
}
$writer->endArticles();
?>