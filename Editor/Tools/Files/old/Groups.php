<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once 'Functions.php';

setToolSessionVar('files','baseWindow','Groups.php');
setToolSessionVar('files','group',0);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Grupper" icon="Element/Folders">'.
'<close link="Library.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Ny gruppe" icon="Element/Folder" overlay="New" link="NewGroup.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="ListOfGroups.php"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>