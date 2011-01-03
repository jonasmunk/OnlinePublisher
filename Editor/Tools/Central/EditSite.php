<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/RemotePublisher.php';
require_once '../../Classes/Request.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id = Request::getInt('id',0);

$site = RemotePublisher::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af system" icon="Basic/Internet">'.
'<close link="index.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateSite.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Navn:" name="title">'.StringUtils::escapeXML($site->getTitle()).'</textfield>'.
'<textfield badge="Adresse:" name="url">'.StringUtils::escapeXML($site->getUrl()).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteSite.php?id='.$id.'"/>'.
'<button title="Annuller" link="index.php"/>'.
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