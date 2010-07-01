<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Support
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%" align="center" margin="10">'.
'<titlebar title="Support" icon="Tool/Help">'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool icon="Web/Page" title="Åben i nyt vindue" overlay="New" link="http://redirect.in2isoft.dk/onlinepublisher/'.$version.'/support/" target="_blank"/>'.
'</toolbar>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="http://redirect.in2isoft.dk/onlinepublisher/"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Frame","Toolbar");
writeGui($xwg_skin,$elements,$gui);
?>