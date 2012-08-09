<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$con = Database::getConnection();
$status = mysql_stat($con);
$server = mysql_get_server_info($con);
$host = mysql_get_host_info($con);
$protocol = mysql_get_proto_info($con);
$client = mysql_get_client_info();

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>array('Property','da'=>'Egenskab'),'width'=>30))->
		header(array('title'=>array('Value','da'=>'Værdi'),'width'=>70))->
	endHeaders()->
	startRow()->
		cell('Server')->cell($server)->
	endRow()->
	startRow()->
		cell('Client')->cell($client)->
	endRow()->
	startRow()->
		cell('Host')->cell($host)->
	endRow()->
	startRow()->
		cell('Protocol')->cell($protocol)->
	endRow()->
	startRow()->
		cell('Status')->cell($status)->
	endRow()->
endList();
?>