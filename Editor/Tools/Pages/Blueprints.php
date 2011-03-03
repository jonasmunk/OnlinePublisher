<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Objects/Pageblueprint.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<titlebar title="Opsætning" icon="Tool/Setting">'.
'<close link="PagesFrame.php"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Rammer" link="Frames.php"/>'.
'<tab title="Specielle sider" link="SpecialPages.php"/>'.
'<tab title="Hierarkier" link="Hierarchies.php"/>'.
'<tab title="Skabeloner" style="Hilited"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Ny skabelon" icon="Element/Template" overlay="New" link="NewBlueprint.php"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Titel"/>'.
'</headergroup>';

$blueprints = Query::after('pageblueprint')->get();
foreach ($blueprints as $blueprint) {
	
	$gui.=
	'<row link="EditBlueprint.php?id='.$blueprint->getId().'">'.
	'<cell>'.
	'<icon icon="'.$blueprint->getIcon().'"/>'.
	'<text>'.In2iGui::escape($blueprint->getTitle()).'</text>'.
	'</cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List");
In2iGui::display($elements,$gui);
?>