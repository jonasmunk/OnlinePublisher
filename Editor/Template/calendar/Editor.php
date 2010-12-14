<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Calendar.php';
require_once '../../Classes/Calendarsource.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/InternalSession.php';

$sql="select * from calendarviewer where page_id=".InternalSession::getPageId();
$row = Database::getRow($sql);
$sql="select object_id as id from calendarviewer_object where page_id=".InternalSession::getPageId();
$objects = Database::getIds($sql);

$sources = Calendarsource::search(array());
$calendars = Calendar::search();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop" onload="parent.Toolbar.location=\'Toolbar.php?\'+Math.random();">'.
'<window xmlns="uri:Window" width="500" align="center" margin="10">'.
'<titlebar title="Kalender">'.
'<close link="../../Tools/Pages/index.php" target="Desktop"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="data">'.
'<group size="Large">'.
'<textfield name="title" badge="Titel:">'.encodeXML($row['title']).'</textfield>'.
'<number name="weekview_starthour" min="0" max="23" badge="Starttid:" value="'.$row['weekview_starthour'].'"/>'.
'<space/>'.
'</group>'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header width="1%"/>'.
'<header title="Kalendere"/>'.
'</headergroup>';
foreach ($calendars as $calendar) {
	$gui.='<row>'.
	'<cell><checkbox value="'.$calendar->getId().'" name="object[]" selected="'.(in_array($calendar->getId(),$objects) ? 'true' : 'false').'"/></cell>'.
	'<cell>'.encodeXML($calendar->getTitle()).'</cell>'.
	'</row>';
}
$gui.=
'</content>'.
'</list>'.
'<list xmlns="uri:List" width="100%" top="10">'.
'<content>'.
'<headergroup>'.
'<header width="1%"/>'.
'<header title="Kilder"/>'.
'</headergroup>';
foreach ($sources as $source) {
	$gui.='<row>'.
	'<cell><checkbox value="'.$source->getId().'" name="object[]" selected="'.(in_array($source->getId(),$objects) ? 'true' : 'false').'"/></cell>'.
	'<cell>'.encodeXML($source->getTitle()).'</cell>'.
	'</row>';
}
$gui.=
'</content>'.
'</list>'.
'<group>'.
'<buttongroup size="Large">'.
'<button title="Luk" link="../../Tools/Pages/index.php" target="Desktop"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","List","Form");
writeGui($xwg_skin,$elements,$gui);
?>