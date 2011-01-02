<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Graph.php';
require_once '../../Classes/InternalSession.php';
require_once 'PagesController.php';

$id = requestGetNumber('id');

$hier = Hierarchy::load($id);
if (!$hier) {
	echo "Not found!";
	// TODO: better error handling
	exit;
}

InternalSession::setToolSessionVar('pages','rightFrame','HierarchyFrame.php?id='.$id);
PagesController::setActiveItem('hierarchy',$id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXML($hier->getName()).'" icon="Element/Structure">'.
'<close link="PagesFrame.php" help="Gå tilbage til listen over sider"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Egenskaber" icon="Basic/Info" link="HierarchyProperties.php?id='.$id.'"/>'.
'<tool title="Rediger" icon="Basic/Edit" link="EditHierarchy.php?id='.$id.'"/>'.
'<tool title="Vis siden" icon="Basic/View" style="Disabled"/>'.
'<divider/>'.
'<tool title="Ny side" icon="Template/Generic" overlay="New" link="NewPageTemplate.php?parent=0&amp;hierarchy='.$id.'&amp;reset=true" help="Opret en ny side i roden af hierarkiet"/>';
if (Graph::canDisplay()) {
	$gui.='<tool title="Vis graf" icon="File/png" link="HierarchyGraph.php?id='.$id.'&amp;format=png" target="Contents"/>';
}
//'<tool title="Vis graf" icon="File/pdf" link="HierarchyGraph.php?id='.$id.'&amp;format=pdf" target="Contents"/>'.
$gui.=
'<flexible/>'.
'<searchfield title="Søgning" width="100" focus="true" name="freetext" method="post" action="PagesFrame.php"/>'.
'</toolbar>'.
'<pathbar>'.
'<item title="'.encodeXML($hier->getName()).'" link="HierarchyFrame.php?id='.$id.'"/>'.
'</pathbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="HierarchyItemChildren.php?hierarchy='.$id.'" name="Contents"/>'.
'</content>'.
'</window>'.
'<internalscript xmlns="uri:Script" source="Frame.js"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame","Script");
writeGui($xwg_skin,$elements,$gui);
?>