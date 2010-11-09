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


ImagesController::setViewType('lastadded');
ImagesController::setGroupId(0);
ImagesController::setViewMode(requestGetText('view'));

if (requestGetBoolean('update')) {
	ImagesController::setUpdateHierarchy(true);
}

$view = ImagesController::getViewMode();

if ($view=='list') {
	$iframeSource = 'ListView.php';
	$viewValue = 'List';
}
elseif ($view=='icon') {
	$iframeSource = 'IconView.php';
	$viewValue = 'Icon';
}
elseif ($view=='gallery') {
	$iframeSource = 'GalleryView.php';
	$viewValue = 'Gallery';
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Seneste billeder" icon="Basic/Time"/>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning" value="'.$viewValue.'">'.
'<view type="List" link="LastAdded.php?view=list"/>'.
'<view type="Icon" link="LastAdded.php?view=icon"/>'.
'<view type="Gallery" link="LastAdded.php?view=gallery"/>'.
'</viewgroup>'.
'<divider/>'.
'<tool title="Tilf&#248;j nyt billede" icon="Tool/Images" overlay="New" link="NewImage.php"/>'.
'<tool title="Ny gruppe" icon="Element/Album" overlay="New" link="NewGroup.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="'.$iframeSource.'" name="IconContent"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>