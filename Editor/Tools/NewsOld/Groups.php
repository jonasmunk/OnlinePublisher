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

NewsController::setViewType('groups');
NewsController::setGroupId(0);
if (requestGetBoolean('noupdate')) {
	NewsController::setUpdateHierarchy(false);
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Alle grupper" icon="Element/Folders">'.
'<close link="Library.php?update=true"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Ny gruppe" icon="Element/Folder" overlay="New" link="NewGroup.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="ListOfGroups.php"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>