<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Image.php';
require_once '../../Classes/Imagegroup.php';
require_once 'ImagesController.php';

$id = requestGetNumber('id');

ImagesController::setImageView('properties');
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
$allGroups = ImageGroup::listAllByTitle();

$groupIds = $image->getGroupIds();

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
'<tool title="Info" icon="Basic/Info" link="ImageInfo.php?id='.$id.'" selected="true"/>'.
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
'</cell><cell>'.
'<form xmlns="uri:Form" action="UpdateImage.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%">'.
'<textfield badge="Titel:" name="title">'.encodeXML($image->getTitle()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="4">'.encodeXML($image->getNote()).'</textfield>'.
'<disclosure label="Avanceret:">'.
'<checkbox badge="Søgbar:" name="searchable" selected="'.($image->isSearchable() ? 'true' : 'false').'"/>'.
'<select badge="Grupper:" name="group[]" lines="8" multiple="true">';
foreach ($allGroups as $grp) {
	$gui.='<option title="'.encodeXML($grp->getTitle()).'" value="'.$grp->getId().'" selected="'.
	(in_array($grp->getId(),$groupIds) ? 'true' : 'false').
	'"/>';
}
$gui.=
'</select>'.
'</disclosure>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="ImageInfo.php?id='.$id.'"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</cell></row></layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Layout","Area","Html","Message");
writeGui($xwg_skin,$elements,$gui);
?>