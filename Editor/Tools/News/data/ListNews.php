<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$sourceId = Request::getInt('source');

if ($sourceId) {
	NewsService::synchronizeSource($sourceId);

	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>array('Title','da'=>'Titel'),'width'=>70));
	$writer->header(array('title'=>array('Date','da'=>'Dato')));
	$writer->endHeaders();
	
	$items = Query::after('newssourceitem')->withProperty('newssource_id',$sourceId)->orderBy('date')->descending()->get();

	foreach ($items as $item) {
		$writer->startRow()->
		startCell(array('icon'=>'common/page'))->
			startLine()->text($item->getTitle())->endLine()->
			startLine(array('dimmed'=>true))->text(StringUtils::shortenString(StringUtils::removeTags($item->getText()),400))->endLine()->
		endCell()->
		startCell()->
			text(DateUtils::formatFuzzy($item->getDate()))->
		endCell()->
		endRow();
	}
	$writer->endList();
	exit;
}

$main = Request::getString('main');
$group = Request::getInt('group');
$type = Request::getString('type');
$queryString = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');

if (!$sort) {
	$sort='title';
}

$query = Query::after('news')->orderBy($sort)->withDirection($direction)->withWindowSize($windowSize)->withWindowPage($windowPage);
$query->withText($queryString);
$query->withCustom('group',$group);

if ($main=='latest') {
	$query->withCreatedMin(DateUtils::addDays(mktime(),-1));
} else if ($main=='active') {
	$query->withCustom('active',true);
} else if ($main=='inactive') {
	$query->withCustom('active',false);
} else if ($main=='url' || $main=='page' || $main=='email' || $main=='file') {
	$query->withCustom('linkType',$main);
}



$result = $query->search();
$objects = $result->getList();

$linkCounts = ObjectLinkService::getLinkCounts($objects);

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ))->
	startHeaders()->
		header(array('title'=>array('Title','da'=>'Titel'),'width'=>40,'key'=>'title','sortable'=>true))->
		header(array('title'=>array('Start date','da'=>'Startdato'),'key'=>'startdate','sortable'=>true))->
		header(array('title'=>array('End date','da'=>'Slutdato'),'key'=>'enddate','sortable'=>true))->
		header(array('width'=>1))->
	endHeaders();

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