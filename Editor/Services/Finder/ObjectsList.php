<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$text = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort','title');
$direction = Request::getString('direction','ascending');
$type = Request::getString('type');

$result = Query::after($type)->withWindowSize($windowSize)->withWindowPage($windowPage)->withDirection($direction)->orderBy($sort)->withText($text)->search();
$list = $result->getList();

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array('total'=>$result->getTotal(),'size'=>$result->getWindowSize(),'page'=>$result->getWindowPage()))->
	startHeaders()->
		header(array('title'=>array('Title','da'=>'Titel'),'width'=>30,'key'=>'title','sortable'=>true))->
	endHeaders();
    foreach ($list as $obj) {
		$writer->startRow(array('id'=>$obj->getId(),'kind'=>$obj->getType()))->
			startCell(['icon'=>$obj->getIcon()])->startWrap()->text($obj->getTitle())->endWrap()->endCell()->
		endRow();
	}
$writer->endList();
?>