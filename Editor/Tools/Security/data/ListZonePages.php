<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Include/Private.php';

$zoneId = Request::getInt('zoneId');

$writer = new ListWriter();

$writer->startList(['selectable' => false])
	->startHeaders()
		->header(['title'=>['Page','da'=>'Side']])
    	->header(['width'=>1])
	->endHeaders();

$sql = "SELECT page.title,page.id from page,securityzone_page where securityzone_page.page_id=page.id and securityzone_page.securityzone_id = @int(zoneId)";

$result = Database::select($sql,['zoneId'=>$zoneId]);
while($row = Database::next($result)) {
	$writer->startRow(array('kind'=>'page','id'=>$row['id']))->
		startCell([ 'icon' => 'common/page' ])->text($row['title'])->endCell()->
        startCell()->icon(['icon'=>'common/delete','action'=>'true','key'=>'remove'])->endCell();
    $writer->endRow();
}
Database::free($result);
$writer->endList();
?>