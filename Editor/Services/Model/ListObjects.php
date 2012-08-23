<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../Include/Private.php';

$type = Request::getString('type');
$queryString = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='title';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowPage' => $windowPage,'sort' => $sort,'direction' => $direction);

if ($type!='') $query['type'] = $type;
if ($queryString!='') $query['query'] = $queryString;

$list = ObjectService::findAny($query);
$objects = $list['result'];

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array('total'=>$list['total'],'size'=>$list['windowSize'],'page'=>$list['windowPage']))->
	startHeaders()->
		header(array('title'=>array('Title','da'=>'Titel'),'key'=>'title','sortable'=>true))->
		header(array('title'=>array('Note','da'=>'Notat'),'width'=>30));
	if ($type=='') {
		$writer->header(array('title'=>'Type','key'=>'type','sortable'=>true,'width'=>20));
	}
	$writer->header(array('title'=>array('Modified','da'=>'Ændret'),'key'=>'updated','sortable'=>true,'width'=>1));
	$writer->endHeaders();

foreach ($objects as $object) {
	$writer->startRow(array('id'=>$object->getId(),'kind'=>$object->getType(),'icon'=>$object->getIn2iGuiIcon(),'title'=>$object->getTitle()))->
		startCell(array('icon'=>$object->getIn2iGuiIcon()))->
			text($object->getTitle())->
		endCell()->
		cell($object->getNote());
		if ($type=='') {
			$writer->cell($object->getType());
		}
		$writer->startCell(array('wrap'=>false))->text(DateUtils::formatDateTime($object->getCreated()))->endCell();
	$writer->endRow();
}

$writer->endList();
?>