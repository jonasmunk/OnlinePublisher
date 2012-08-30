<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>'Design','width'=>40))->
		header(array('title'=>array('Key','da'=>'Nøgle'),'width'=>30))->
	endHeaders();

$designs = Query::after('design')->get();
foreach ($designs as $item) {
	$writer->
	startRow(array('kind'=>'design','id'=>$item->getId()))->
		startCell(array('icon'=>$item->getIcon()))->text($item->getTitle())->endCell()->
		startCell()->text($item->getUnique())->endCell()->
	endRow();
}
$writer->endList();
?>