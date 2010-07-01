<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once 'ImagesController.php';

ImagesController::setViewType('groups');
ImagesController::setGroupId(0);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Oversigt over grupper" icon="Element/Album">'.
'<close link="Library.php?update=true"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Ny gruppe" icon="Element/Album" overlay="New" link="NewGroup.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="ListOfAlbums.php"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>