<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$queryString = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort','title');
$direction = Request::getString('direction','ascending');
$group = Request::getInt('group',0);

$query = Query::after('file')->withWindowSize($windowSize)->withWindowPage($windowPage)->withDirection($direction)->orderBy($sort)->withText($queryString);
if ($group) {
	$query->withCustom('group',$group);
}
$result = $query->search();

$objects = $result->getList();

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array('total'=>$result->getTotal(),'size'=>$result->getWindowSize(),'page'=>$result->getWindowPage()))->
	startHeaders()->
		header(array('title'=>'Titel','width'=>30,'key'=>'title','sortable'=>true))->
	endHeaders();
	foreach ($objects as $object) {
		$writer->startRow(array('id'=>$object->getId(),'kind'=>$object->getType(),'icon'=>$object->getIn2iGuiIcon(),'title'=>$object->getTitle()))->
			startCell(array('icon'=>$object->getIn2iGuiIcon()))->startWrap()->text($object->getTitle())->endWrap()->endCell()->
		endRow();
	}
$writer->endList();
?>