<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'Functions.php';

setNewsGroup(0);
if (requestGetExists('view')) {
	setNewsView(requestGetText('view'));
}
$view = getNewsView();

if ($view=='list')
	$iframeSource = 'NewsList.php';
else
	$iframeSource = 'NewsIcons.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Nyheder" icon="Tool/News"/>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning">';
if ($view=='list')
	$gui.='<view type="List" style="Hilited"/>';
else
	$gui.='<view type="List" link="NewsFrame.php?view=list"/>';
if ($view=='icon')
	$gui.='<view type="Icon" style="Hilited"/>';
else
	$gui.='<view type="Icon" link="NewsFrame.php?view=icon"/>';
$gui.=
'</viewgroup>'.
'<divider/>'.
'<tool title="Opret nyhed" icon="Part/News" overlay="New" link="NewNews.php"/>'.
'<tool title="Opret gruppe" icon="Element/Folder" overlay="New" link="NewNewsgroup.php"/>'.
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