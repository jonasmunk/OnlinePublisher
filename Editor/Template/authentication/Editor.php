<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$sql="select * from authentication where page_id=".getPageId();
$row = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop" onload="parent.Toolbar.location=\'Toolbar.php?\'+Math.random();">'.
'<window xmlns="uri:Window" width="400" align="center" top="20">'.
'<titlebar title="Autentifikation" icon="Template/Generic">'.
'<close link="../../Tools/Pages/index.php" target="Desktop"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="title">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.encodeXML($row['title']).'</textfield>'.
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