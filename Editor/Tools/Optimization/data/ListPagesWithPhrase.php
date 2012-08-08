<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

$phrase = Testphrase::load($id);

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>array('Page','da'=>'Side')))->
		header(array('width'=>1))->
	endHeaders();

$sql = "select id,title from page where lower(`index`) like '%".strtolower($phrase->getTitle())."%'";

$result = Database::select($sql);
while ($row = Database::next($result)) {
	$writer->startRow(array('id'=>$row['id'],'kind'=>'page'))->
		startCell(array('icon'=>'common/page'))->
			text($row['title'])->
		endCell()->
		startCell()->
			startIcons()->
				icon(array('icon' => 'monochrome/view','action'=>true,'revealing'=>true,'data' => array('action' => 'view')))->
				icon(array('icon' => 'monochrome/edit','action'=>true,'revealing'=>true,'data' => array('action' => 'edit')))->
			endIcons()->
		endCell()->
	endRow();
}
Database::free($result);

$writer->endList();
?>