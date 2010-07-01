<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$sql="select * from html where page_id=".getPageId();
$row = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop" onload="parent.Toolbar.location=\'Toolbar.php?\'+Math.random();">'.
'<window xmlns="uri:Window" width="100%" align="center" margin="10">'.
'<titlebar title="HTML">'.
'<close link="../../Tools/Pages/index.php" target="Desktop"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar">'.
'<tool title="Upload HTML-dokument" icon="File/html" overlay="Upload" link="Upload.php"/>'.
'</toolbar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="data">'.
'<group size="Small" badgeplacement="above">'.
'<textfield badge="Titel:" name="title">'.encodeXML($row['title']).'</textfield>'.
'<textfield badge="HTML-kode:" name="html" lines="20">'.encodeXML($row['html']).'</textfield>'.
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

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>