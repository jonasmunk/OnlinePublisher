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
require_once '../../Classes/GuiUtils.php';
require_once 'ImagesController.php';

$id = requestGetNumber('id');

ImagesController::setImageView('info');
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
'<window xmlns="uri:Window" width="500" align="center">'.
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
'<tool title="Info" icon="Basic/Info" selected="true"/>'.
'<tool title="Vis billedet" icon="Basic/View" link="ImageView.php?id='.$id.'"/>'.
'</toolbar>'.
'<content padding="5" background="true" valign="top">'.
'<layout xmlns="uri:Layout" width="100%"><row><cell width="144" valign="top">'.
'<area xmlns="uri:Area" width="140" height="140"><content align="center" valign="middle">'.
'<html xmlns="uri:Html">'.
'<a href="ImageView.php?id='.$id.'">'.
'<img src="../../../util/images/?id='.$image->getId().'&amp;maxwidth=128&amp;maxheight=128&amp;8&amp;format=jpg&amp;timestamp='.$image->getUpdated().'" border="0"/>'.
'</a>'.
'</html>'.
'</content></area>'.
'</cell><cell valign="top">'.
'<overview xmlns="uri:Overview" width="100%">'.
'<group>'.
'<block badge="Titel:">'.encodeXML(shortenString($image->getTitle(),40)).'</block>'.
'<block badge="Beskrivelse:">'.encodeXML($image->getNote()).'</block>'.
'<block badge="Filnavn:">'.encodeXML(shortenString($image->getFilename(),40)).'</block>'.
'<block badge="Bredde:">'.encodeXML($image->getWidth()).' px</block>'.
'<block badge="H&#248;jde:">'.encodeXML($image->getHeight()).' px</block>'.
'<block badge="St&#248;rrelse:">'.GuiUtils::bytesToString($image->getSize()).'</block>'.
'</group>'.
'</overview>'.
'<group size="Small" align="right" xmlns="uri:Button" top="10">'.
'<button title="Rediger egenskaber..." link="ImageProperties.php?id='.$id.'"/>'.
'</group>'.
'</cell></row></layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Overview","Layout","Area","Html","Message","Button");
writeGui($xwg_skin,$elements,$gui);
?>