<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>'Bruger','width'=>20))->
		header(array('title'=>'Tidspunkt','width'=>20))->
		header(array('title'=>'Beskrivelse'))->
		header(array('width'=>1))->
	endHeaders();

$sql="select page_history.id,UNIX_TIMESTAMP(page_history.time) as time,page_history.message,object.title".
" from page_history left join object on object.id=page_history.user_id where page_id=".Database::int($pageId)." order by page_history.time desc";

$result = Database::select($sql);
while ($row = Database::next($result)) {
	$writer->startRow(array('id'=>$row['id']))->
		startCell(array('icon'=>'common/user'))->text($row['title'])->endCell()->
		startCell(array('wrap'=>false))->text(DateUtils::formatLongDateTime($row['time']))->endCell()->
		startCell()->text($row['message'])->startIcons()->icon(array('icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>array('action'=>'editMessage')))->endIcons()->endCell()->
		startCell()->button(array('text'=>array('View','da'=>'Vis')))->endCell()->
		endRow();
}
Database::free($result);

$writer->endList();
?>