<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$available = ToolService::getAvailable();
$installed = ToolService::getInstalled();

$writer = new ListWriter();

$writer->startList(array('unicode'=>true))->
	startHeaders()->
		header(array('title'=>'Værktøj','width'=>40))->
		header(array('title'=>'Nøgle','width'=>30))->
		header(array('title'=>'','width'=>1))->
	endHeaders();

foreach ($available as $key) {
	$info = ToolService::getInfo($key);
	$writer->
	startRow(array('kind'=>'tool','id'=>$key))->
		startCell(array('icon'=>$info ? $info->icon : 'common/tools'));
		if ($info) {
			$writer->startLine()->text($info ? $info->name->da : $key)->endLine()->
				startLine(array('dimmed'=>true))->text($info ? StringUtils::fromUnicode($info->description->da) : $key)->endLine();
		} else {
			$writer->text($key);
		}
		$writer->endCell()->
		startCell()->text($key)->endCell();
		if (in_array($key,$installed)) {
			$writer->startCell()->
				button(array('text'=>'Uninstall','data'=>array('action'=>'uninstallTool','key'=>$key)))->
			endCell();
		} else {
			$writer->startCell()->
				button(array('text'=>'Install','data'=>array('action'=>'installTool','key'=>$key)))->
			endCell();
		}
	$writer->endRow();
}
$writer->endList();
?>