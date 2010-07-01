<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
//require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Feed.php';
require_once '../../Classes/UserInterface.php';


$parser = new FeedParser();
$feed = $parser->parseURL('http://twitter.com/statuses/user_timeline/16827706.rss');

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Titel','width'=>40));
$writer->header(array('title'=>'Beskrivelse','width'=>30));
$writer->header(array('title'=>'Dato','width'=>30));
$writer->endHeaders();

foreach($feed->getItems() as $item) {
	$writer->startRow();
	$writer->startCell()->text($item->getTitle())->endCell();
	$writer->startCell()->text($item->getDescription())->endCell();
	$writer->startCell()->text(UserInterface::presentDateTime($item->getPubDate()))->endCell();
	$writer->endRow();
}
$writer->endList();
?>