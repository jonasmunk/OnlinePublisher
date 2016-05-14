<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>'Name','width'=>40))->
		header(array('title'=>'Parent'))->
		header(array('title'=>'Properties'))->
	endHeaders();

$list = ClassService::getClasses();
foreach ($list as $item) {
	$writer->startRow(array('kind'=>'class','id'=>$item['name']))->
		startCell(array('icon'=>'common/object'))->
			startLine()->text($item['name'])->endLine()->
			startLine(array('dimmed'=>true,'minor'=>true))->text($item['relativePath'])->endLine()->
		endCell()->
		startCell()->text($item['parent'])->endCell()->
		startCell()->text(Strings::toJSON($item['properties']))->endCell()->
	endRow();
}
$writer->endList();
?>