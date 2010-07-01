<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Feed.php';
require_once '../../Classes/UserInterface.php';

$url = 'http://www.in2isoft.dk/services/news/rss/?group=373';

$parser = new FeedParser();
$feed = $parser->parseURL($url);

$writer = new ArticlesWriter();

$writer->startArticles();

foreach($feed->getItems() as $item) {
	$writer->startArticle();
	$writer->startTitle()->text($item->getTitle())->endTitle();
	$writer->startParagraph()->text($item->getDescription())->endParagraph();
	$writer->startParagraph(array('dimmed'=>true))->text(UserInterface::presentDateTime($item->getPubDate()))->endParagraph();
	//$writer->startCell()->text()->endCell();
	$writer->endArticle();
}
$writer->endArticles();
?>