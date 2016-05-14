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
		->header(['title'=>['User','da'=>'Bruger']])
    	->header(['width'=>1])
	->endHeaders();

$sql = "SELECT object.title,object.id from object,securityzone_user where securityzone_user.user_id=object.id and securityzone_user.securityzone_id = @int(zoneId)";

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