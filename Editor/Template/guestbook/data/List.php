<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Guestbook
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>'Tid / Navn','width'=>30))->
		header(array('title'=>'Besked'))->
		header(array('width'=>1))->
	endHeaders();
$sql="select UNIX_TIMESTAMP(time) as time,name,text,id from guestbook_item where page_id=".Database::int(Request::getId())." order by time desc";
Log::debug($sql);
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$writer->startRow(array('id'=>$row['id']))->
		startCell(array('wrap'=>true))->
			startLine()->text($row['name'])->endLine()->
			startLine(array('minor'=>true,'dimmed'=>true,'top'=>5))->text(DateUtils::formatLongDateTime($row['time']))->endLine()->
		endCell()->
		startCell()->text($row['text'])->endCell()->
		startCell()->startIcons()->icon(array('icon'=>'monochrome/delete','action'=>true,'revealing'=>true,'data'=>array('action'=>'delete')))->endIcons()->endCell()->
	endRow();
}
Database::free($result);
$writer->endList();
?>