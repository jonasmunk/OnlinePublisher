<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.GuestBook
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Utilities/StringUtils.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop" onload="parent.Toolbar.location=\'Toolbar.php?\'+Math.random();">'.
'<window xmlns="uri:Window" width="500" align="center" margin="10">'.
'<titlebar title="Gæstebog">'.
'<close link="../../Tools/Pages/index.php" target="_parent"/>'.
'</titlebar>'.
'<tabgroup size="Large" align="center">'.
'<tab title="Tekst" link="Text.php"/>'.
'<tab title="Emner" style="Hilited"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header title="Tid"/>'.
'<header title="Navn"/>'.
'<header title="Besked"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$sql="select * from guestbook_item where page_id=".InternalSession::getPageId()." order by time desc";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row>'.
	'<cell>'.StringUtils::escapeXML($row['time']).'</cell>'.
	'<cell>'.StringUtils::escapeXML($row['name']).'</cell>'.
	'<cell>'.StringUtils::escapeXML($row['text']).'</cell>'.
	'<cell><icon icon="Basic/Delete" link="RemoveItem.php?id='.$row['id'].'"/></cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List");
writeGui($xwg_skin,$elements,$gui);
?>