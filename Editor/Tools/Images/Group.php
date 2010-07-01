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
require_once '../../Classes/Imagegroup.php';

require_once 'ImagesController.php';


ImagesController::setViewType('group');
if (requestGetBoolean('update')) {
	ImagesController::setUpdateHierarchy(true);
}


if (requestGetExists('id')) {
	ImagesController::setGroupId(requestGetNumber('id',0));
}
$id = ImagesController::getGroupId();

ImagesController::setViewMode(requestGetText('view'));
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

$group = ImageGroup::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXML($group->getTitle()).'" icon="Element/Album">'.
'<close link="Library.php?update=true"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<viewgroup title="Visning" value="'.$viewValue.'">'.
'<view type="List" link="Group.php?view=list"/>'.
'<view type="Icon" link="Group.php?view=icon"/>'.
'<view type="Gallery" link="Group.php?view=gallery"/>'.
'</viewgroup>'.
'<divider/>'.
'<tool title="Tilf&#248;j nyt billede" icon="Tool/Images" overlay="New" link="NewImage.php"/>'.
'<tool title="Tilf&#248;j eksisterende" icon="Tool/Images" overlay="Attach" link="AddToGroup.php"/>'.
($view=='list' ?
'<tool title="Fjern fra gruppe" icon="Element/Album" overlay="Close" link="javascript: List.getDocument().forms[0].submit();"/>'
: '').
'<flexible/>'.
'<tool title="Egenskaber" icon="Element/Album" overlay="Info" link="GroupProperties.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="'.$iframeSource.'" object="List" name="IconContent"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>