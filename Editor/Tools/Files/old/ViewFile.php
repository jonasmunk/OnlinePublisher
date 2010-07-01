<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require '../../../Config/Setup.php';
require '../../Include/Security.php';
require '../../Include/XmlWebGui.php';
require '../../Include/Functions.php';
require '../../Classes/GuiUtils.php';
require 'Functions.php';

$id = requestGetNumber('id',0);
setFilePropertiesView('info');
$group = getFileGroup();

$sql="select * from file where id=".$id;
$row = Database::selectFirst($sql);

$title=$row['title'];
$description=$row['description'];
$filename=$row['filename'];

if ($group>0) {
	$sql="select * from filegroup where id=".$group;
	$row = Database::selectFirst($sql);
	$groupTitle=$row['title'];
}


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<parent title="Filer" link="FileFrame.php"/>';
if ($group>0) $gui.='<parent title="'.encodeXML($groupTitle).'" link="Group.php"/>';
$gui.='<titlebar title="'.encodeXML($title).'" icon="'.GuiUtils::getFileIcon($filename).'">'.
'<close link="'.($group>0 ? 'Group.php' : 'Library.php').'"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Luk" icon="Basic/Stop" link="'.($group>0 ? 'Group.php' : 'Library.php').'"/>'.
'<divider/>'.
'<tool title="Slet" icon="Basic/Delete" link="DeleteFile.php?id='.$id.'"/>'.
'<flexible/>'.
'<tool title="Info" icon="Basic/Info" link="File.php?id='.$id.'"/>'.
'<tool title="Egenskaber" icon="'.GuiUtils::getFileIcon($filename).'" overlay="Properties" link="FileProperties.php?id='.$id.'"/>'.
'<tool title="Se filen" icon="Basic/Search" selected="true"/>'.
'</toolbar>'.
'<content background="true" valign="top">'.
'<iframe xmlns="uri:Frame" source="../../../files/'.$filename.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>