<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';

if (requestGetExists('position')) {
	setupSetPosition(requestGetText('position'));
}

$pos = setupGetPosition();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<meta><title>OnlinePublisher opsætning</title></meta>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="600" height="400" align="center" top="30">'.
'<titlebar title="OnlinePublisher opsætning">'.
'<close link="Authentication.php?logout=true"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar">'.
'<tool title="Introduktion" icon="Basic/Info"'.
($pos=='Intro' ? ' selected="true"' : ' link="?position=Intro"').
'/>'.
'<divider/>'.
'<tool title="Database" icon="Element/Database"'.
($pos=='Database' ? ' selected="true"' : ' link="?position=Database"').
'/>'.
'<tool title="Administrator" icon="Role/Administrator"'.
($pos=='Users' ? ' selected="true"' : ' link="?position=Users"').
'/>'.
'<tool title="Skabeloner" icon="Element/Template"'.
($pos=='Templates' ? ' selected="true"' : ' link="?position=Templates"').
'/>'.
'<tool title="Værktøjer" icon="Tool/Generic"'.
($pos=='Tools' ? ' selected="true"' : ' link="?position=Tools"').
'/>'.
'<tool title="Mapper" icon="Element/Folder"'.
($pos=='Folders' ? ' selected="true"' : ' link="?position=Folders"').
'/>'.
'</toolbar>'.
'<content background="true">'.
'<layout xmlns="uri:Layout" height="100%" width="100%" spacing="8">'.
'<row>'.
'<cell valign="top">'.
'<iframe xmlns="uri:Frame" source="'.$pos.'.php"/>'.
'</cell>'.
'</row>'.
'<row>'.
'<cell height="1%" align="right">'.
'<group xmlns="uri:Button" size="Large">'.
'<button title="Afslut" link="Authentication.php?logout=true"/>'.
'</group>'.
'</cell>'.
'</row>'.
'</layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';
$elements = array("Window","Toolbar","Layout","Button","Frame");
writeGui($xwg_skin,$elements,$gui);
?>
