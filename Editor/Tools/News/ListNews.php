<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/News.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/DateUtils.php';
require_once '../../Classes/Services/ObjectLinkService.php';
require_once '../../Classes/Services/NewsService.php';
require_once '../../Classes/Log.php';

$sourceId = Request::getInt('source');

if ($sourceId) {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>'Titel','width'=>70));
	$writer->header(array('title'=>'Dato'));
	$writer->endHeaders();
	NewsService::synchronizeSource($sourceId);
	
	$items = Query::after('newssourceitem')->withProperty('newssource_id',$sourceId)->orderBy('date')->descending()->get();

	foreach ($items as $item) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/page'))->
			startLine()->text($item->getTitle())->endLine()->
			startLine(array('dimmed'=>true))->text(StringUtils::shortenString(StringUtils::removeTags($item->getText()),400))->endLine()->
			endCell();
		$writer->startCell()->text(DateUtils::formatFuzzy($item->getDate()))->endCell();
		
		$writer->endRow();
	}
	$writer->endList();
	exit;

	
	if ($source = Newssource::load($sourceId)) {
		$parser = new FeedParser();
		$feed = $parser->parseURL($source->getUrl());
		foreach ($feed->getItems() as $item) {
			$writer->startRow();
			$writer->startCell(array('icon'=>'common/page'))->
				startLine()->text($item->getTitle())->endLine()->
				startLine(array('dimmed'=>true))->text(StringUtils::shortenString(StringUtils::removeTags($item->getDescription()),400))->endLine()->
				endCell();
			$writer->startCell()->text(DateUtils::formatFuzzy($item->getPubDate()))->endCell();
			
			$writer->endRow();
		}
	}
	$writer->endList();
	exit;
}

$main = Request::getString('main');
$group = Request::getInt('group');
$type = Request::getString('type');
$queryString = Request::getUnicodeString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='title';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowPage' => $windowPage,'ordering' => $sort,'direction' => $direction);

if ($type!='') $query['type'] = $type;
if ($queryString!='') $query['query'] = $queryString;

if ($group>0) {
	$query['group'] = $group;
}
if ($type) {
	$query['type'] = $type;
}
if ($main=='latest') {
	$query['createdMin']=DateUtils::addDays(mktime(),-1);
} else if ($main=='active') {
	$query['active']=true;
} else if ($main=='inactive') {
	$query['active']=false;
} else if ($main=='url' || $main=='page' || $main=='email' || $main=='file') {
	$query['linkType']=$main;
}
$list = News::search2($query);
$objects = $list['result'];

$linkCounts = ObjectLinkService::getLinkCounts($objects);

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>'Titel','width'=>40,'key'=>'title','sortable'=>true));
$writer->header(array('title'=>'Startdato','key'=>'startdate','sortable'=>true));
$writer->header(array('title'=>'Slutdato','key'=>'enddate','sortable'=>true));
$writer->header(array('width'=>1));
$writer->endHeaders();

foreach ($objects as $object) {
	$active = false;
	if ($object->getStartDate()==null && $object->getEndDate()==null) {
		$active = true;
	} else if ($object->getEndDate()>time()) {
		$active = true;
	} else if ($object->getEndDate()==null && $object->getStartDate()<time()) {
		$active = true;
	}
	$writer->startRow(array('kind'=>'news','id'=>$object->getId(),'icon'=>$object->getIn2iGuiIcon(),'title'=>$object->getTitle()));
	$writer->startCell(array('icon'=>$object->getIn2iGuiIcon()))->text($object->getTitle())->endCell();
	$writer->startCell();
	$writer->text(DateUtils::formatDateTime($object->getStartdate()))->endCell();
	$writer->startCell()->text(DateUtils::formatDateTime($object->getEnddate()))->endCell();
	$writer->startCell()->startIcons();
	if (!$active) {
		$writer->icon(array('icon'=>'monochrome/invisible'));	
	}
	//$writer->icon(array('icon'=>($active ? 'monochrome/play' : 'monochrome/invisible')));
	if ($linkCounts[$object->getId()]>0) {
		$writer->icon(array('icon'=>"monochrome/link"));
	}
	$writer->endIcons()->endCell();
	$writer->endRow();
}
$writer->endList();
?>