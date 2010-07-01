<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Filegroup.php';

if (requestGetExists('id')) {
	setToolSessionVar('files','group',requestGetNumber('id'));
}
$id = getToolSessionVar('files','group');

if (requestGetExists('view')) {
	setToolSessionVar('files','view',requestGetText('view'));
}
$view = getToolSessionVar('files','view','icon');

if ($view=='list') {
	$iframeSource = 'FileList.php';
}
else {
	$iframeSource = 'FileIcons.php';
}
setToolSessionVar('files','baseWindow','Group.php');

$group = FileGroup::load($id);
$title=$group->getTitle();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXML($title).'" icon="Element/Folder">'.
'<close link="Library.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning">';
if ($view=='list')
	$gui.='<view type="List" style="Hilited"/>';
else
	$gui.='<view type="List" link="Group.php?view=list"/>';
if ($view=='icon')
	$gui.='<view type="Icon" style="Hilited"/>';
else
	$gui.='<view type="Icon" link="Group.php?view=icon"/>';
$gui.=
'</viewgroup>'.
'<divider/>'.
'<tool title="Upload ny fil" icon="File/Generic" overlay="Upload" link="NewFile.php"/>'.
'<tool title="Tilf&#248;j til gruppe" icon="Element/Folder" overlay="Add" link="AddToGroup.php"/>'.
($view=='list' ?
'<tool title="Fjern fra gruppe" icon="Element/Folder" overlay="Remove" link="javascript: List.getDocument().forms[0].submit();"/>'
: '').
'<flexible/>'.
'<tool title="Egenskaber" icon="Element/Folder" overlay="Info" link="GroupProperties.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="'.$iframeSource.'" object="List"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>