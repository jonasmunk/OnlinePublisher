<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once 'NewsController.php';

NewsController::setViewType('all');
NewsController::setGroupId(0);
if (requestGetBoolean('noupdate')) {
	NewsController::setUpdateHierarchy(false);
}


NewsController::setViewMode(requestGetText('view'));
$view = NewsController::getViewMode();
if ($view=='List') {
	$iframeSource = 'NewsList.php';
}
elseif ($view=='Icon') {
	$iframeSource = 'NewsIcons.php';
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Alle nyheder" icon="Tool/News"/>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning" value="'.$view.'">'.
'<view type="List" link="Library.php?view=List"/>'.
'<view type="Icon" link="Library.php?view=Icon"/>'.
'</viewgroup>'.
'<divider/>'.
'<tool title="Ny nyhed" icon="Part/News" overlay="New" link="NewNews.php"/>'.
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