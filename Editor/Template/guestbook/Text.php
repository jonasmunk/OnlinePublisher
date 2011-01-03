<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.GuestBook
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Utilities/StringUtils.php';

$sql="select * from guestbook where page_id=".InternalSession::getPageId();
$row = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop" onload="parent.Toolbar.location=\'Toolbar.php?\'+Math.random();">'.
'<window xmlns="uri:Window" width="500" align="center" margin="10">'.
'<titlebar title="Gæstebog">'.
'<close link="../../Tools/Pages/index.php" target="Desktop"/>'.
'</titlebar>'.
'<tabgroup size="Large" align="center">'.
'<tab title="Tekst" style="Hilited"/>'.
'<tab title="Emner" link="Items.php"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="title">'.
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
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>