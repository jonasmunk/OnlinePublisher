<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';

$view = getRequestToolSessionVar('organisation','view','view','list');

if ($view=='list')
	$iframeSource = 'RoleList.php';
else
	$iframeSource = 'RoleIcons.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Roller" icon="Role/Administrator">'.
'<close link="Library.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning">';
if ($view=='list')
	$gui.='<view type="List" style="Hilited"/>';
else
	$gui.='<view type="List" link="Roles.php?view=list"/>';
if ($view=='icon')
	$gui.='<view type="Icon" style="Hilited"/>';
else
	$gui.='<view type="Icon" link="Roles.php?view=icon"/>';
$gui.=
'</viewgroup>'.
'<divider/>'.
'<tool title="Ny rolle" icon="Role/Administrator" overlay="New" link="NewRole.php"/>'.
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