<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Utilities/GuiUtils.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Services/TemplateService.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$templates = TemplateService::getTemplatesKeyed();
if (Request::exists('id')) {
	$id = Request::getInt('id');
	$sql = "select hierarchy_item.*,page.id as pageid,page.title as pagetitle,template.unique as templateunique,file.object_id as fileid,file.filename from hierarchy_item left join page on page.id = hierarchy_item.target_id left join template on template.id=page.template_id left join file on file.object_id = hierarchy_item.target_id where parent=".$id." order by `hierarchy_item`.`index`";
	$return = 'HierarchyItemChildren.php%3Fid='.$id;
}
elseif (Request::exists('hierarchy')) {
	$id = Request::getInt('hierarchy');
	$sql = "select hierarchy_item.*,page.id as pageid,page.title as pagetitle,template.unique as templateunique,file.object_id as fileid,file.filename from hierarchy_item left join page on page.id = hierarchy_item.target_id left join template on template.id=page.template_id left join file on file.object_id = hierarchy_item.target_id where parent=0 and hierarchy_id=".$id." order by `hierarchy_item`.`index`";
	$return = 'HierarchyItemChildren.php%3Fhierarchy='.$id;
} 

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Underpunkter" width="100%"/>'.
'<header title="" width="1%"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="HierarchyItemFrame.php?id='.$row['id'].'" target="_parent">'.
	'<cell>'.
	'<icon size="1" icon="'.GuiUtils::getLinkIcon($row['target_type'],$row['templateunique'],$row['filename']).'"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>';
	if ($row['pageid']>0) {
		$gui.=
		'<icon size="1" icon="Basic/Edit" link="javascript:window.parent.parent.location=\'../../Template/Edit.php?id='.$row['pageid'].'\'" help="Rediger siden"/>'.
		'<icon size="1" icon="Basic/View" link="javascript:window.parent.parent.location=\'../../Services/Preview/?id='.$row['pageid'].'&amp;return=Tools/Pages/\'" help="Se siden"/>'.
		'<icon size="1" icon="Basic/Info" link="EditPage.php?id='.$row['pageid'].'" help="Rediger sidens egenskaber"/>';
	}
	$gui.=
	'</cell>'.
	'<cell>'.
	'<direction direction="Up" link="MoveHierarchyItem.php?id='.$row['id'].'&amp;dir=-1&amp;return='.$return.'" target="_self" help="Flyt menupunktet op"/>'.
	'<direction direction="Down" link="MoveHierarchyItem.php?id='.$row['id'].'&amp;dir=1&amp;return='.$return.'" target="_self" help="Flyt menupunktet ned"/>'.
	'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';
$elements = array("List");
writeGui($xwg_skin,$elements,$gui);
?>