<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Image.php';
require_once 'Functions.php';

$id = requestGetNumber('id',0);
setImagePropertiesView('view');

$group=getImageGroup();

$image = Image::load($id);


if ($group>0) {
	$sql="select * from object where type='imagegroup' and id=".$group;
	$row = Database::selectFirst($sql);
	$parent=$row['title'];
	$close='Group.php';
}
else {
	$parent='Bibliotek';
	$close='Library.php';
}


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%" align="center">'.
'<sheet width="300" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du virkelig slette billedet?</title>'.
'<description>Handlingen kan ikke fortrydes og billedet fjernes fra alle sider.</description>'.
($group>0 ? '<description>Du kan også vælge kun at fjerne billedet fra dette album men beholde det i biblioteket.</description>' : '').
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();" style="Hilited"/>'.
($group>0 ? '<button title="Fjern fra album" link="RemoveFromGroup.php?id='.$id.'"/>' : '').
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
'<flexible/>'.
'<tool title="Info" icon="Basic/Info" link="ImageInfo.php?id='.$id.'"/>'.
'<tool title="Egenskaber" icon="Tool/Images" overlay="Properties" link="ImageProperties.php?id='.$id.'"/>'.
'<tool title="Se billedet" icon="Basic/Search" selected="true"/>'.
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