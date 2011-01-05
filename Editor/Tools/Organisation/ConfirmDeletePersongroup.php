<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="350" align="center" top="30">'.
'<titlebar title="Advarsel">'.
'<close link="PersongroupProperties.php?id='.$id.'"/>'.
'</titlebar>'.
'<content background="true">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Er du sikker p&#229; at du vil slette persongruppen?</title>'.
'<description>Handlingen kan ikke fortrydes. Tilh&#248;rende personer slettes IKKE.</description>'.
'<buttongroup size="Large">'.
'<button title="Nej, slet ikke" link="PersongroupProperties.php?id='.$id.'"/>'.
'<button title="Ja, slet" link="DeletePersongroup.php?id='.$id.'" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Message");
writeGui($xwg_skin,$elements,$gui);
?>