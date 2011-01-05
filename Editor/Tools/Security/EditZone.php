<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Securityzone.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';
require_once '../../Include/XmlWebGui.php';

$id = Request::getInt('id',0);

$zone = SecurityZone::load($id);


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af beskyttet område" icon="Zone/Security">'.
'<close link="index.php"/>'.
'</titlebar>'.
//'<tabgroup size="Large">'.
//'<tab title="Egenskaber" style="Hilited"/>'.
//'<tab title="Brugere" link="EditZoneUsers.php?id='.$id.'"/>'.
//'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateZone.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($zone->getTitle()).'</textfield>'.
'<select badge="Autentifikationsside:" name="page" selected="'.$zone->getAuthenticationPageId().'">'.
'<option value="0"/>'.
GuiUtils::buildPageOptions('authentication').
'</select>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteZone.php?id='.$id.'"/>'.
'<button title="Annuller" link="index.php"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("Window","Form");
writeGui($xwg_skin,$elements,$gui);
?>