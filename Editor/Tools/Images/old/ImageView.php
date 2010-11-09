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
require_once '../../Classes/Image.php';
require_once '../../Classes/Imagegroup.php';
require_once 'ImagesController.php';

$id = requestGetNumber('id');

ImagesController::setImageView('view');
$close = ImagesController::getBaseWindow();
$parent = ImagesController::getBaseTitle();
$image = Image::load($id);

$groupId = ImagesController::getGroupId();
if ($groupId>0) {
	$group = ImageGroup::load($groupId);
	$parent=$group->getTitle();
} else {
	$group = false;
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%" align="center">'.
'<sheet width="300" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du virkelig slette billedet?</title>'.
'<description>Handlingen kan ikke fortrydes og billedet fjernes fra alle sider.</description>'.
($group ? '<description>Du kan også vælge kun at fjerne billedet fra denne gruppe men beholde det i biblioteket.</description>' : '').
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();" style="Hilited"/>'.
($group ? '<button title="Fjern fra gruppe" link="RemoveFromGroup.php?id='.$id.'"/>' : '').
'<button title="Slet fra bibliotek" link="DeleteImage.php?id='.$id.'"/>'.
'</buttongroup>'.
'</message>'.
'</sheet>'.
'<parent title="'.encodeXML($parent).'" link="'.$close.'"/>'.
'<titlebar title="'.encodeXML($image->getTitle()).'" icon="Element/Image">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Luk" icon="Basic/Close" link="'.$close.'"/>'.
'<divider/>'.
'<tool title="Slet" icon="Basic/Delete" link="javascript:ConfirmDelete.show();"/>'.
'<tool title="Download" icon="Basic/Download" link="DownloadImage.php?id='.$id.'"/>'.
'<tool title="Erstat" icon="Basic/Refresh" link="ReplaceImage.php?id='.$id.'"/>'.
'<flexible/>'.
'<tool title="Info" icon="Basic/Info" link="ImageInfo.php?id='.$id.'"/>'.
'<tool title="Vis billedet" icon="Basic/View" selected="true"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="ImageDisplayer.php?id='.$id.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame","Message");
writeGui($xwg_skin,$elements,$gui);
?>