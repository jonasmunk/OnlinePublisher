<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Services/TemplateService.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';
require_once 'PagesController.php';

$templates = TemplateService::getTemplatesKeyed();
$active = PagesController::getActiveItem();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<selection xmlns="uri:Selection" object="Selection" value="'.$active['type'].'">'.
'<item icon="Template/Generic" title="Alle sider" value="allpages"/>'.
'<title>Hierarkier</title>'.
'</selection>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" selection="'.$active['type'].'-'.$active['id'].'" unique="Tools.Pages.Hierarchy">';

$sql="select * from hierarchy order by name";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<element icon="Element/Structure" title="'.StringUtils::escapeXML($row['name']).'" link="HierarchyFrame.php?id='.$row['id'].'"'.
	' target="Right" unique="hierarchy-'.$row['id'].'" info="{type:\'hierarchy\',id:'.$row['id'].'}"'.
	'>'.
	buildHier($row['id'],0).
	'</element>';
}
Database::free($result);
$gui.=
'</hierarchy>'.
'<menu xmlns="uri:Menu" object="ContextMenuPage" width="130">'.
'<item title="Rediger side" link="javascript: hierDelegate.editPage();"/>'.
'<item title="Sidens egenskaber" link="javascript: hierDelegate.pageProperties();"/>'.
'<separator/>'.
'<item title="Vis underpunkter" link="javascript: hierDelegate.showSubItems();"/>'.
'<item title="Rediger menupunkt" link="javascript: hierDelegate.editItem();"/>'.
'<item title="Flyt op" link="javascript: hierDelegate.moveItem(-1);"/>'.
'<item title="Flyt ned" link="javascript: hierDelegate.moveItem(1);"/>'.
'</menu>'.
'<menu xmlns="uri:Menu" object="ContextMenu" width="130">'.
'<item title="Vis underpunkter" link="javascript: hierDelegate.showSubItems();"/>'.
'<item title="Rediger menupunkt" link="javascript: hierDelegate.editItem();"/>'.
'<item title="Flyt op" link="javascript: hierDelegate.moveItem(-1);"/>'.
'<item title="Flyt ned" link="javascript: hierDelegate.moveItem(1);"/>'.
'</menu>'.
'<menu xmlns="uri:Menu" object="ContextMenuHierarchy" width="130">'.
'<item title="Hierarkiets egenskaber" link="javascript: hierDelegate.hierarchyProperties();"/>'.
'<item title="Rediger hierarki" link="javascript: hierDelegate.editHierarchy();"/>'.
'<item title="Vis underpunkter" link="javascript: hierDelegate.showSubItems();"/>'.
'</menu>'.
'<refresh xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="5000"/>'.
'<script xmlns="uri:Script" source="js/Hierarchy.js"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Hierarchy","Script","Selection","Menu");
writeGui($xwg_skin,$elements,$gui);

function buildHier($id,$parent) {
	global $templates;
	$output="";
	$sql="select hierarchy_item.*,page.id as pageid,page.title as pagetitle,template.unique as templateunique,file.object_id as fileid,file.filename from hierarchy_item left join page on page.id = hierarchy_item.target_id left join template on template.id=page.template_id left join file on file.object_id = hierarchy_item.target_id where hierarchy_item.parent=".$parent." and hierarchy_item.hierarchy_id=".$id." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.=
		'<element icon="'.GuiUtils::getLinkIcon($row['target_type'],$row['templateunique'],$row['filename']).'"'.
		' title="'.StringUtils::escapeXML($row['title']).'"'.
		' style="'.($row['hidden'] ? 'Disabled' : 'Standard').'"'.
		' unique="item-'.$row['id'].'"'.
		' link="HierarchyItem.php?id='.$row['id'].'" target="Right" info="{itemId:\''.$row['id'].'\',type:\''.$row['target_type'].'\',pageId:'.($row['pageid'] && $row['target_type']=='page' ? $row['pageid'] : 0).'}">'.
		buildHier($id,$row['id']).
		'</element>';
	}
	Database::free($result);
	return $output;
}
?>