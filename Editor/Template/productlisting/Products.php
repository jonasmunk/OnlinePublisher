<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ProductListing
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';
require_once 'Functions.php';

$id = getProductListingId();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Produktliste">'.
'<close link="../../Tools/Pages/index.php" target="_parent"/>'.
'</titlebar>'.
'<tabgroup align="center" size="Large">'.
'<tab title="Tekst" link="Text.php"/>'.
'<tab title="Produkter" style="Hilited"/>'.
'</tabgroup >'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Gem" icon="Basic/Save" link="javascript:document.forms[0].submit();"/>'.
'</toolbar>'.
'<content background="Window">'.
'<form xmlns="uri:Form" action="UpdateProducts.php" method="post">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="·" align="center" width="1%"/>'.
'<header title="Titel" width="30%"/>'.
'<header title="Beskrivelse" width="70%"/>'.
'</headergroup>';

$selected = array();
$sql="select productgroup_id from productlisting_productgroup where page_id=".$id;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$selected[]=$row['productgroup_id'];
}
Database::free($result);

$sql="select object.* from object where object.type='productgroup' order by object.title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.=
	'<row>'.
	'<cell><checkbox name="group[]" value="'.$row['id'].'" selected="'.(in_array($row['id'],$selected) ? 'true' : 'false').'"/></cell>'.
	'<cell>'.
	'<icon size="1" icon="Element/Folder"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.StringUtils::escapeXMLBreak($row['title'],'<break>').'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</form>'.
'</content>'.
'</window>'.
'<script xmlns="uri:Script">parent.Toolbar.location="Toolbar.php?"+Math.random();</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Script","Form");
writeGui($xwg_skin,$elements,$gui);
?>