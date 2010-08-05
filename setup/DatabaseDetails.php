<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Info/Database.php';
require_once '../Editor/Classes/DatabaseUtil.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<area xmlns="uri:Area" width="100%">'.
'<tabgroup size="Large">'.
'<tab title="Opdatering" link="Database.php"/>'.
'<tab title="Detaljer" style="Hilited"/>'.
'</tabgroup>'.
'<content padding="5">'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header title="Tabel"/>'.
'<header title="Kolonner" align="right"/>'.
'<header title="Status" align="center"/>'.
'</headergroup>';

$tables = DatabaseUtil::getTables();
foreach ($tables as $table) {
	$columns = DatabaseUtil::getTableColumns($table);
	$errors = DatabaseUtil::checkTable($table,$columns);
	$gui.='<row>'.
	'<cell>'.$table.'</cell>'.
	'<cell>'.count($columns).'</cell>'.
	'<cell>'.(count($errors)==0 ? '<status type="Finished"/>' : implode('<break/>',$errors)).'</cell>'.
	'</row>';
}
$missingTables=DatabaseUtil::findMissingTables($tables);
foreach ($missingTables as $table) {
	$gui.='<row>'.
	'<cell>'.$table.'</cell>'.
	'<cell>?</cell>'.
	'<cell>Findes ikke!</cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","List");
writeGui($xwg_skin,$elements,$gui);
?>