<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
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
'<close link="Image.php?id='.$id.'"/>'.
'</titlebar>'.
'<content background="true">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Er du sikker p&#229; at du vil slette billedet?</title>'.
'<description>Handlingen kan ikke fortrydes og billedet fjernes fra evt. sider.</description>'.
'<buttongroup size="Large">'.
'<button title="Nej, slet ikke" link="Image.php?id='.$id.'"/>'.
'<button title="Ja, slet" link="DeleteImage.php?id='.$id.'" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Message");
writeGui($xwg_skin,$elements,$gui);
?>