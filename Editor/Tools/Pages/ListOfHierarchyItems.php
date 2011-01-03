<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Services/TemplateService.php';
require_once 'Functions.php';

$hierarchyId = requestGetNumber('id');
$templates = TemplateService::getTemplatesKeyed();

$gui=
'<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="50%"/>'.
'<header title="Link" width="50%"/>'.
'<header title="" width="1%"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$gui.= buildList($hierarchyId,0,0);

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List");
writeGui($xwg_skin,$elements,$gui);

function buildList($hierarchyId,$parent,$level) {
	global $templates;
	$gui='';
	$sql="select hierarchy_item.*,page.id as pageid,page.title as pagetitle,template.unique as templateunique,file.object_id as fileid,file.filename,fileobject.title as filetitle from hierarchy_item left join page on page.id = hierarchy_item.target_id left join file on file.object_id = hierarchy_item.target_id left join object as fileobject on file.object_id=fileobject.id left join template on template.id=page.template_id".
	" where hierarchy_id=".$hierarchyId." and parent=".$parent." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$text='';
		if ($row['target_type']=='page') {
			$text = $row['pagetitle'];
			if ($row['pageid']=='') {
				$text = 'Siden findes ikke mere!';
			}
		}
		else if ($row['target_type']=='pageref') {
			$text = $row['pagetitle'].' (ref)';
			if ($row['pageid']=='') {
				$text = 'Siden findes ikke mere!';
			}
		}
		else if ($row['target_type']=='file') {
			$text = $row['filetitle'];
			if ($row['fileid']=='') {
				$text = 'Filen findes ikke mere!';
			}
		}
		else if ($row['target_type']=='url') {
			$text = $row['target_value'];
		}
		else if ($row['target_type']=='email') {
			$text = $row['target_value'];
		}
		$gui.=
		'<row link="EditHierarchyItem.php?id='.$row['id'].'&amp;return=EditHierarchy.php%3Fid='.$hierarchyId.'" target="_parent">'.
		'<cell>';
		for ($i=0;$i<$level;$i++) {
			$gui.=' ·· ';
		}
		$gui.=
		encodeXML($row['title']).
		'</cell>'.
		'<cell>';
		if ($row['target_type']!='') {
			$gui.='<icon size="1" icon="'.GuiUtils::getLinkIcon($row['target_type'],$row['templateunique'],$row['filename']).'"/>';
		}
		$gui.=
		'<text>'.encodeXML($text).'</text>'.
		'</cell>'.
		'<cell>'.
		'<icon icon="Basic/Add" link="NewHierarchyItem.php?parent='.$row['id'].'&amp;hierarchy='.$hierarchyId.'&amp;return=EditHierarchy.php%3Fid='.$hierarchyId.'" target="_parent" help="Opret et nyt underpunkt til dette menupunkt"/>'.
		'</cell>'.
		'<cell>'.
		'<direction direction="Up" link="MoveHierarchyItem.php?id='.$row['id'].'&amp;dir=-1&amp;return=EditHierarchy.php%3Fid='.$hierarchyId.'" target="_parent" help="Flyt menupunktet op"/>'.
		'<direction direction="Down" link="MoveHierarchyItem.php?id='.$row['id'].'&amp;dir=1&amp;return=EditHierarchy.php%3Fid='.$hierarchyId.'" target="_parent" help="Flyt menupunktet ned"/>'.
		'</cell>'.
		'</row>'.
		buildList($hierarchyId,$row['id'],$level+1);
	}
	Database::free($result);
	return $gui;
}
?>