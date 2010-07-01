<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = requestGetNumber('id',0);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="350" align="center" top="30">'.
'<titlebar title="Advarsel">'.
'<close link="RoleProperties.php?id='.$id.'"/>'.
'</titlebar>'.
'<content background="true">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Er du sikker p&#229; at du vil slette rollen?</title>'.
'<description>Handlingen kan ikke fortrydes.</description>'.
'<buttongroup size="Large">'.
'<button title="Nej, slet ikke" link="RoleProperties.php?id='.$id.'"/>'.
'<button title="Ja, slet" link="DeletePersonrole.php?id='.$id.'" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Message");
writeGui($xwg_skin,$elements,$gui);
?>