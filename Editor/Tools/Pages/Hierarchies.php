<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Model/Hierarchy.php';
require_once '../../Classes/Utilities/StringUtils.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<titlebar title="Opsætning" icon="Tool/Setting">'.
'<close link="PagesFrame.php"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Rammer" link="Frames.php"/>'.
'<tab title="Specielle sider" link="SpecialPages.php"/>'.
'<tab title="Hierarkier" style="Hilited"/>'.
'<tab title="Skabeloner" link="Blueprints.php"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Nyt hierarki" icon="Element/Structure" overlay="New" link="NewHierarchy.php"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Navn" width="85%"/>'.
'<header align="center" title="Sprog" width="15%"/>'.
'</headergroup>';

$hiers = Hierarchy::search();
foreach ($hiers as $hier) {
	$name = $hier->getName();
	$gui.=
	'<row link="HierarchyProperties.php?id='.$hier->getId().'&amp;return=Hierarchies.php">'.
	'<cell>'.
	'<icon icon="Element/Structure"/>'.
	'<text>'.StringUtils::escapeXML($name).'</text>'.
	'</cell>'.
	'<cell>'.xwgBuildListLanguageIcon($hier->getLanguage()).'</cell>'.
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
writeGui($xwg_skin,$elements,$gui);
?>