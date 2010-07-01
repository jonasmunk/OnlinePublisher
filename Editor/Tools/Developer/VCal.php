<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$url = 'http://ical.mac.com/jonasmunk/Privat.ics';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%" align="center">'.
'<titlebar title="Session" icon="Basic/Time"/>'.
'<toolbar xmlns="uri:Toolbar">'.
'<searchfield title="URL" target="Contents" action="VCalContent.php" name="url" width="400" value="'.$url.'"/>'.
'</toolbar>'.
'<content>'.
'<iframe xmlns="uri:Frame" name="Contents" source="VCalContent.php?url='.$url.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Frame","Toolbar");
writeGui($xwg_skin,$elements,$gui);
?>