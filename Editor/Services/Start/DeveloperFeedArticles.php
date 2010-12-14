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

$url = 'http://twitter.com/statuses/user_timeline/16827706.rss';
$parser = new FeedParser();
$feed = $parser->parseURL($url);

if (!$feed) {
	In2iGui::respondFailure();
	exit;
}

$writer = new ArticlesWriter();

$writer->startArticles();

foreach($feed->getItems() as $item) {
	$title = $item->getTitle();
	$title = str_replace('in2isoft: ','',$title);
	$writer->startArticle();
	$writer->startTitle()->text($title)->endTitle();
	$writer->startParagraph(array('dimmed'=>true))->text(DateUtils::formatDateTime($item->getPubDate()))->endParagraph();
	$writer->endArticle();
}
$writer->endArticles();
?>