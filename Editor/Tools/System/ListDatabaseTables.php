<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Info/Database.php';
require_once '../../Classes/DatabaseUtil.php';
require_once '../../Include/XmlWebGui.php';




header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>
<list>
<headers>
	<header title="Tabel" width="30"/>
	<header title="Kolonner" width="20"/>
	<header title="Status" width="50"/>
</headers>';

$tables = DatabaseUtil::getTables();
foreach ($tables as $table) {
	$columns = DatabaseUtil::getTableColumns($table);
	$errors = DatabaseUtil::checkTable($table,$columns);
	echo '
	<row>
	<cell>'.$table.'</cell>
	<cell>'.count($columns).'</cell>
	<cell>'.implode('<break/>',$errors).'</cell>
	</row>
	';
}
$missingTables=DatabaseUtil::findMissingTables($tables);
foreach ($missingTables as $table) {
	echo '
	<row>
	<cell>'.$table.'</cell>
	<cell>?</cell>
	<cell>Table not in database</cell>
	</row>
	';
}
echo '</list>';
?>