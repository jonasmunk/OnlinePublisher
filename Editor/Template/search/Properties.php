<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$id = getSearchId();

$sql="select * from search where page_id=".$id;
$row = Database::selectFirst($sql);
$title = $row['title'];
$text = $row['text'];
$buttontitle = $row['buttontitle'];

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Søgeside">'.
'<close link="../../Tools/Pages/index.php" target="_parent"/>'.
'</titlebar>'.
'<tabgroup align="center" size="Large">'.
'<tab title="Egenskaber" style="Hilited"/>'.
'<space/>'.
'<tab title="Sider" link="Pages.php"/>'.
'<tab title="Billeder" link="Images.php"/>'.
'<tab title="Filer" link="Files.php"/>'.
'<tab title="Personer" link="Persons.php"/>'.
'<tab title="Nyheder" link="News.php"/>'.
'<tab title="Produkter" link="Products.php"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="data">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($title).'</textfield>'.
'<textfield badge="Tekst:" name="text" lines="6">'.StringUtils::escapeXML($text).'</textfield>'.
'<textfield badge="Knap:" name="buttontitle">'.StringUtils::escapeXML($buttontitle).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Luk" link="../../Tools/Pages/index.php" target="_parent"/>'.
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