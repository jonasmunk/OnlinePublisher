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
$group = Request::getInt('group',0);


$result = PageQuery::rows()->withText($text)->search();

$objects = $result->getList();

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array('total'=>$result->getTotal(),'size'=>$result->getWindowSize(),'page'=>$result->getWindowPage()))->
	startHeaders()->
		header(array('title'=>'Titel','width'=>30,'key'=>'title','sortable'=>true))->
	endHeaders();
	foreach ($objects as $row) {
		$writer->startRow(array('id'=>$row['id'],'kind'=>'page','icon'=>'common/page','title'=>$row['title']))->
			startCell(array('icon'=>'common/page'))->startWrap()->text($row['title'])->endWrap()->endCell()->
		endRow();
	}
$writer->endList();
?>