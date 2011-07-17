<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/ListWriter.php';
require_once '../../Classes/Object.php';
require_once '../../Classes/Request.php';

$queryString = Request::getUnicodeString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='title';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowPage' => $windowPage,'sort' => $sort,'direction' => $direction);

$query['type'] = 'file';
if ($queryString!='') {
	$query['query'] = $queryString;
}

$list = Object::find($query);
$objects = $list['result'];

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array('total'=>$list['total'],'size'=>$list['windowSize'],'page'=>$list['windowPage']))->
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