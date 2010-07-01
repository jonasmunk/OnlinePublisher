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
require_once 'Functions.php';

setToolSessionVar('files','group',0);
setToolSessionVar('files','baseWindow','Library.php');

if (requestGetExists('view')) {
	setToolSessionVar('files','view',requestGetText('view'));
}
$view = getToolSessionVar('files','view','icon');

if ($view=='list')
	$iframeSource = 'FileList.php';
else
	$iframeSource = 'FileIcons.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Bibliotek" icon="Tool/Files"/>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning">';
if ($view=='list')
	$gui.='<view type="List" style="Hilited"/>';
else
	$gui.='<view type="List" link="Library.php?view=list"/>';
if ($view=='icon')
	$gui.='<view type="Icon" style="Hilited"/>';
else
	$gui.='<view type="Icon" link="Library.php?view=icon"/>';
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