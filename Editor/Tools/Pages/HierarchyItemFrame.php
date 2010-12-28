<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Services/TemplateService.php';
require_once 'Functions.php';
require_once 'PagesController.php';

$templates = TemplateService::getTemplatesKeyed();
$id = requestGetNumber('id');

setToolSessionVar('pages','rightFrame','HierarchyItemFrame.php?id='.$id);
PagesController::setActiveItem('item',$id);

$sql = "select hierarchy_item.*,page.id as pageid,page.title as pagetitle,template.unique as templateunique,file.object_id as fileid,file.filename from hierarchy_item left join page on page.id = hierarchy_item.target_id left join template on template.id=page.template_id left join file on file.object_id = hierarchy_item.target_id where hierarchy_item.id=".$id;
if (!$row = Database::selectFirst($sql)) {
	echo "Not found!";
	exit;
}

$icon = GuiUtils::getLinkIcon($row['target_type'],$row['templateunique'],$row['filename']);
		
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXML($row['title']).'" icon="'.$icon.'">'.
'<close link="PagesFrame.php" help="Gå tilbage til listen over sider"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">';
if ($row['target_type']=='page' && $row['pageid']>0) {
	$gui.=
    '<tool title="Egenskaber" icon="Basic/Info" link="EditPage.php?id='.$row['target_id'].'" help="Rediger sidens egenskaber"/>'.
	'<tool title="Rediger" icon="Basic/Edit" link="../../Template/Edit.php?id='.$row['target_id'].'" target="Desktop" help="Rediger sidens indhold"/>'.
	'<tool title="Vis siden" icon="Basic/View" link="../../Services/Preview/?id='.$row['target_id'].'&amp;return=Tools/Pages/" target="Desktop" help="Se siden"/>';
}
else {
	$gui.=
    '<tool title="Egenskaber" icon="Basic/Info" link="EditHierarchyItem.php?id='.$id.'" help="Rediger egenskaber for menupunktet"/>'.
	'<tool title="Rediger" icon="Basic/Edit" style="Disabled"/>'.
//	'<tool title="Sidens egenskaber" icon="Template/Generic" overlay="Info" style="Disabled"/>'.
	'<tool title="Vis siden" icon="Basic/View" style="Disabled"/>';
}
$gui.=
'<divider/>'.
'<tool title="Ny side" icon="Template/Generic" overlay="New" link="NewPageTemplate.php?parent='.$id.'&amp;hierarchy='.$row['hierarchy_id'].'&amp;reset=true" help="Opret en ny side som underpunkt til dette menupunkt"/>'.
'<flexible/>'.
'<searchfield title="Søgning" width="100" focus="true" name="freetext" method="post" action="PagesFrame.php"/>'.
'</toolbar>'.
'<pathbar>'.
buildPath($id).
'</pathbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="HierarchyItemChildren.php?id='.$id.'" name="Pages"/>'.
'</content>'.
'</window>'.
'<internalscript xmlns="uri:Script" source="Frame.js"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame","Script");
writeGui($xwg_skin,$elements,$gui);

function buildPath($id) {
    $gui='';
    $path = Hierarchy::getItemPath($id);
    foreach ($path as $item) {
        if ($item['type']=='item') {
            $gui.='<item title="'.encodeXML($item['title']).'" link="HierarchyItemFrame.php?id='.$item['id'].'" target="Right"/>';            
        }
        elseif ($item['type']=='hierarchy') {
            $gui.='<item title="'.encodeXML($item['title']).'" link="HierarchyFrame.php?id='.$item['id'].'" target="Right"/>';            
        }
    }
    return $gui;
}
?>