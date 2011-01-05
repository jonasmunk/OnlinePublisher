<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Info/Database.php';
require_once '../Editor/Classes/DatabaseUtil.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';


$tables = DatabaseUtil::getTables();

$log = array();
$log[]="== Starter opdatering! ==";
$missingTables=DatabaseUtil::findMissingTables($tables);
foreach ($missingTables as $table) {
	$action = "CREATE TABLE `".$table."` (";
	$columns = DatabaseUtil::getExpectedColumns($table);
	$keys = '';
	for ($i=0;$i<count($columns);$i++) {
		$column=$columns[$i];
		if ($i>0) $action.=",";
		$action.="`".$column[0]."` ".$column[1];
		if ($column[2]=='') {
			$action.=" NOT NULL";
		}
		if ($column[3]=='PRI') {
			$keys.=",PRIMARY KEY (`".$column[0]."`)";
		}
		if ($column[4]!='') {
			$action.=" DEFAULT '".$column[4]."'";
		}
		if ($column[5]!='') {
			$action.=" ".$column[5];
		}
	}
	$action.=$keys;
	$action.=")";
	$log[] = "";
	$log[] = "Kommando:";
	$log[] = $action;
	$con = Database::getConnection();
	mysql_query($action,$con);
	$error = mysql_error($con);
	if (strlen($error)>0) {
		$log[] = "!!!Fejl: ".$error;
	}
}

foreach ($tables as $table) {
	$columns = DatabaseUtil::getTableColumns($table);
	$errors = DatabaseUtil::checkTable($table,$columns);
	if (count($errors)>0) {
		$sql=DatabaseUtil::updateTable($table,$columns);
		foreach ($sql as $action) {
			$log[] = "";
			$log[] = "Kommando:";
			$log[] = $action;
			$con = Database::getConnection();
			mysql_query($action,$con);
			$error = mysql_error($con);
			if (strlen($error)>0) {
				$log[] = "!!!Fejl: ".$error;
			}
		}
	}
}
DatabaseUtil::setAsUpToDate();

$log[] = "";
$log[] = "== Opdatering afsluttet ==";


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<tabgroup size="Large">'.
'<tab title="Opdatering" style="Hilited"/>'.
'<tab title="Detaljer" link="DatabaseDetails.php"/>'.
'</tabgroup>'.
'<content padding="5">'.
'<form xmlns="uri:Form">'.
'<group size="Large" badgeplacement="above">'.
'<textfield badge="Log:" lines="8">';

foreach ($log as $line) {
	$gui.=$line."\n";
}

$gui.=
'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Tilbage" link="Database.php"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Button","Form");
writeGui($xwg_skin,$elements,$gui);
?>