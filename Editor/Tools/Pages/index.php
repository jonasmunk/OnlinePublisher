<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';

$right = InternalSession::getToolSessionVar('pages','rightFrame');
if ($right==null) {
	InternalSession::setToolSessionVar('pages','rightFrame','PagesFrame.php');
	$right = 'PagesFrame.php';
}

$action = Request::getString('action');
if ($action=='newpage') {
	$right = 'NewPageTemplate.php?reset=true';
} elseif ($action=='pageproperties') {
	$id = Request::getInt('id');
	if (!($id>0)) {
		$id = InternalSession::getPageId();
	}
	$right = 'EditPage.php?id='.$id;
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="10">'.
'<row><cell width="250">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="0">'.
'<row><cell>'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Oversigt"/>'.
'<content>'.
'<iframe xmlns="uri:Frame" name="HierFrame" source="Hierarchy.php"/>'.
'</content>'.
'</area>'.
'</cell></row>'.
'<row><cell height="90" top="10">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<content align="let" valign="middle">'.
'<group xmlns="uri:Icon" size="2" spacing="5" titles="right">'.
'<row>'.
'<icon title="Ny side" icon="Template/Generic" overlay="New" link="NewPageTemplate.php?reset=true" target="Right" help="Opret en ny side"/>'.
'<icon title="Udgiv ændringer" icon="Basic/Internet" overlay="Upload" link="../../Services/Publish/?close=../../Tools/Pages/" target="Right" help="Udgiv ændringer foretaget på hjemmesiden"/>'.
'</row>'.
'</group>'.
'<group xmlns="uri:Icon" size="1" spacing="9" titles="right" width="1%">'.
'<row>'.
'<icon title="Opsætning" icon="Tool/Setting" link="Frames.php" target="Right" help="Opsætning..."/>';
	$gui.='<icon title="Avanceret" icon="Tool/System" link="Import.php" target="Right" help="Udgiv ændringer foretaget på hjemmesiden"/>';
$gui.=
'</row>'.
'</group>'.
'</content>'.
'</area>'.
'</cell></row>'.
'</layout>'.
'</cell><cell>'.
'<iframe xmlns="uri:Frame" source="'.$right.'" name="Right"/>'.
'</cell></row>'.
'</layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Layout","Frame","Area","Icon");
writeGui($xwg_skin,$elements,$gui);
?>