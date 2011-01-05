<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.PersonListing
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$id = getPersonListingId();

$sql="select * from personlisting where page_id=".$id;
$row = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Personliste">'.
'<close link="../../Tools/Pages/index.php" target="Desktop"/>'.
'</titlebar>'.
'<tabgroup align="center" size="Large">'.
'<tab title="Tekst" style="Hilited"/>'.
'<tab title="Personer" link="Persons.php"/>'.
'</tabgroup >'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="data">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($row['title']).'</textfield>'.
'<textfield badge="Tekst:" name="text" lines="6">'.StringUtils::escapeXML($row['text']).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Luk" link="../../Tools/Pages/index.php" target="Desktop"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'<script xmlns="uri:Script">parent.Toolbar.location="Toolbar.php?"+Math.random();</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Script");
writeGui($xwg_skin,$elements,$gui);
?>