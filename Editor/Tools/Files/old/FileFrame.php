<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require '../../../Config/Setup.php';
require '../../Include/Security.php';
require '../../Include/XmlWebGui.php';
require '../../Include/Functions.php';
require 'Functions.php';

setFileGroup(0);
if (requestGetExists('view')) {
	setFileView(requestGetText('view'));
}
$view = getFileView();

if ($view=='list')
	$iframeSource = 'FileList.php';
else
	$iframeSource = 'FileIcons.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Filer" icon="Tool/Files"/>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning">';
if ($view=='list')
	$gui.='<view type="List" style="Hilited"/>';
else
	$gui.='<view type="List" link="FileFrame.php?view=list"/>';
if ($view=='icon')
	$gui.='<view type="Icon" style="Hilited"/>';
else
	$gui.='<view type="Icon" link="FileFrame.php?view=icon"/>';
$gui.=
'</viewgroup>'.
'<divider/>'.
'<tool title="Upload ny fil" icon="File/Generic" overlay="Upload" link="NewFile.php"/>'.
'<tool title="Ny gruppe" icon="Element/Folder" overlay="New" link="NewGroup.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="'.$iframeSource.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>