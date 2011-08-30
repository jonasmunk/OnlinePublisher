<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$available = TemplateService::getAvailableTemplates();
$installed = TemplateService::getInstalledTemplateKeys();
$used = TemplateService::getUsedTemplates();

$writer = new ListWriter();

$writer->startList(array('unicode'=>true))->
	startHeaders()->
		header(array('title'=>'Template','width'=>40))->
		header(array('title'=>'Nøgle','width'=>30))->
		header(array('title'=>'Anvendt','width'=>30))->
		header(array('title'=>'','width'=>1))->
	endHeaders();

$designs = Query::after('design')->get();
foreach ($available as $key) {
	$info = TemplateService::getTemplateInfo($key);
	$writer->
	startRow(array('kind'=>'template','id'=>$key))->
		startCell(array('icon'=>'common/page'))->
			startLine()->text($info['name'])->endLine()->
			startLine(array('dimmed'=>true))->text($info['description'])->endLine()->
		endCell()->
		startCell()->text($key)->endCell();
		if (in_array($key,$installed)) {
			$writer->startCell()->icon(array('icon'=>in_array($key,$used) ? 'common/success' : 'common/stop'))->endCell();
			if (!in_array($key,$used)) {
				$writer->startCell()->
					button(array('text'=>'Uninstall','data'=>array('action'=>'uninstallTemplate','key'=>$key)))->
				endCell();
			} else {
				$writer->startCell()->endCell();
			}
		} else {
			$writer->startCell()->endCell();
			$writer->startCell()->
				button(array('text'=>'Install','data'=>array('action'=>'installTemplate','key'=>$key)))->
			endCell();
		}
	$writer->endRow();
}
$writer->endList();
?>