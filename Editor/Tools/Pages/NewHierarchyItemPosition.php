<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Services/TemplateService.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$return = Request::getString('return');
$id = Request::getInt('id',0);
$templates = TemplateService::getTemplatesKeyed();


$sql="select * from hierarchy_item where id=".$id;
$row = Database::selectFirst($sql);
$hierarchyId = $row['hierarchy_id'];
$parentId = $row['parent'];


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window width="400" align="center" xmlns="uri:Window">'.
'<titlebar title="Flytning af menupunkt" icon="Element/Structure">'.
'<close link="EditHierarchyItem.php?id='.$id.'" help="Luk vinduet uden at gemme ændringer"/>'.
'</titlebar>'.
'<content padding="10" background="true">'.
'<text align="center" xmlns="uri:Text">'.
'<strong>Vælg ny position</strong>'.
'<break/><small>Vælg det menupunkt som menupunktet skal være et underpunkt til...</small>'.
'</text>'.
'<area width="100%" top="10" bottom="10" xmlns="uri:Area">'.
'<content>'.
'<overflow height="300" xmlns="uri:Layout">'.
'<hierarchy xmlns="uri:Hierarchy">';

$sql="select * from hierarchy where id=".$hierarchyId;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.=
	'<element icon="Element/Structure" title="'.StringUtils::escapeXML($row['name']).'" open="true"';
	if ($parentId!=0) {
	    $gui.=' link="UpdateHierarchyItemPosition.php?id='.$id.'&amp;newParent=0&amp;return='.urlencode($return).'"';
    } else {
        $gui.=' style="Disabled"';
    }
	$gui.='>'.
	buildHier($row['id'],0,$id,$parentId,$return).
	'</element>';
}
Database::free($result);

$gui.=
'</hierarchy>'.
'</overflow>'.
'</content>'.
'</area>'.
'<group size="Large" align="right" xmlns="uri:Button">'.
'<button title="Annuller" link="EditHierarchyItem.php?id='.$id.'&amp;return='.urlencode($return).'" help="Luk vinduet uden at gemme ændringer"/>'.
'</group>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Hierarchy","Text","Button","Area","Layout");
writeGui($xwg_skin,$elements,$gui);

function buildHier($id,$parent,$itemId,$itemParentId,$return) {
	global $templates;
	$output="";
	$sql="select hierarchy_item.*,page.id as pageid,page.title as pagetitle,template.unique as templateunique,file.object_id as fileid,file.filename from hierarchy_item left join page on page.id = hierarchy_item.target_id left join template on template.id=page.template_id left join file on file.object_id = hierarchy_item.target_id where hierarchy_item.parent=".$parent." and hierarchy_item.hierarchy_id=".$id." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.=
		'<element icon="'.GuiUtils::getLinkIcon($row['target_type'],$row['templateunique'],$row['filename']).'" title="'.StringUtils::escapeXML($row['title']).'"';
		if ($row['id']==$itemParentId || $row['id']==$itemId) {
		    $output.=' style="Disabled"';
		} else {
		    $output.=' link="UpdateHierarchyItemPosition.php?id='.$itemId.'&amp;newParent='.$row['id'].'&amp;return='.urlencode($return).'"';
		}
		$output.='>';
		if ($row['id']!=$itemId) {
		    $output.=buildHier($id,$row['id'],$itemId,$itemParentId,$return);
	    }
		$output.='</element>';
	}
	Database::free($result);
	return $output;
}
?>