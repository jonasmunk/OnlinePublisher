<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<titlebar title="S&#248;gning" icon="Basic/Search"/>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Ny s&#248;gezone" icon="Basic/Network" overlay="New" link="#"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Navn" width="100%"/>'.
'<header align="center"/>'.
'</headergroup>';

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Form");
writeGui($xwg_skin,$elements,$gui);
?>