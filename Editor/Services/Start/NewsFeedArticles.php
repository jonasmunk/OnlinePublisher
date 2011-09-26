<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
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

$writer = new ListWriter();

$writer->startList(array('unicode'=>true));

foreach($feed->getItems() as $item) {
	$writer->startRow()->
		startCell()->startLine()->startStrong()->text(StringUtils::fromUnicode($item->getTitle()))->endStrong()->endLine()->
			startLine(array('minor'=>true))->text(StringUtils::fromUnicode($item->getDescription()))->endline()->
			startLine(array('dimmed'=>true,'mini'=>true))->text(DateUtils::formatFuzzy($item->getPubDate()))->endLine()->
		endCell()->
		startCell();
		if (StringUtils::isNotBlank($item->getLink())) {
			$writer->button(array('text'=>StringUtils::fromUnicode('Læs'),'data'=>array('url'=>$item->getLink())));
		}
		$writer->endCell()->
	endRow();
		
}
$writer->endList();
?>