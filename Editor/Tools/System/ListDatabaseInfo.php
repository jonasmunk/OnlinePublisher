<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/DatabaseUtil.php';

$con = Database::getConnection();
$status = mysql_stat($con);
$server = mysql_get_server_info($con);
$host = mysql_get_host_info($con);
$protocol = mysql_get_proto_info($con);
$client = mysql_get_client_info();
$tables = DatabaseUtil::getTables();

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>
<list>
<headers>
	<header title="Egenskab" width="30"/>
	<header title="Værdi" width="70"/>
</headers>
<row>
	<cell>Server</cell><cell>'.$server.'</cell>
</row>
<row>
	<cell>Klient</cell><cell>'.$client.'</cell>
</row>
<row>
	<cell>Vært</cell><cell>'.$host.'</cell>
</row>
<row>
	<cell>Protokol</cell><cell>'.$protocol.'</cell>
</row>
<row>
	<cell>Status</cell><cell>'.$status.'</cell>
</row>
</list>';
?>