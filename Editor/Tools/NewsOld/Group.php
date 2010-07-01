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
require_once '../../Classes/Newsgroup.php';
require_once 'NewsController.php';


NewsController::setViewType('group');
if (requestGetExists('id')) {
	NewsController::setGroupId(requestGetNumber('id',0));
}
$id = NewsController::getGroupId();
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


$group = NewsGroup::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXML(shortenString($group->getTitle(),30)).'" icon="Element/Folder">'.
'<close link="Library.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning" value="'.$view.'">'.
'<view type="List" link="Group.php?view=List"/>'.
'<view type="Icon" link="Group.php?view=Icon"/>'.
'</viewgroup>'.
'<divider/>'.
'<tool title="Opret nyhed" icon="Part/News" overlay="New" link="NewNews.php"/>'.
'<tool title="Tilf&#248;j til gruppe" icon="Element/Folder" overlay="Add" link="AddToNewsgroup.php"/>'.
($view=='List' ?
'<tool title="Fjern fra gruppe" icon="Element/Folder" overlay="Close" link="javascript: List.getDocument().forms[0].submit();"/>'
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