<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$id = Request::getInt('id',0);
setImagePropertiesView('view');

$group=getImageGroup();


$sql="select * from image where object_id=".$id;
$row = Database::selectFirst($sql);

$title=$row['title'];
$description=$row['description'];
$filename=$row['filename'];

if ($group>0) {
	$sql="select * from imagegroup where id=".$group;
	$row = Database::selectFirst($sql);
	$groupTitle=$row['title'];
}


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<parent title="Billeder" link="Library.php"/>';
if ($group>0) $gui.='<parent title="'.StringUtils::escapeXML($groupTitle).'" link="Group.php"/>';
$gui.='<titlebar title="'.StringUtils::escapeXML($title).'" icon="Tool/Images">'.
'<close link="'.($group>0 ? 'Group.php' : 'Library.php').'"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Luk" icon="Basic/Stop" link="'.($group>0 ? 'Group.php' : 'Library.php').'"/>'.
'<divider/>'.
'<tool title="Slet" icon="Basic/Delete" link="ConfirmDelete.php?id='.$id.'"/>'.
'<flexible/>'.
'<tool title="Info" icon="Basic/Info" link="ImageInfo.php?id='.$id.'"/>'.
'<tool title="Egenskaber" icon="Tool/Images" overlay="Properties" link="ImageProperties.php?id='.$id.'"/>'.
'<tool title="Se billedet" icon="Basic/Search" selected="true"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="../../../images/'.$filename.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>