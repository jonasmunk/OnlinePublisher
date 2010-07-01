<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/File.php';
require_once '../../Classes/Filegroup.php';
require_once 'Functions.php';

setToolSessionVar('files','fileView','properties');

$id = requestGetNumber('id',0);

$file = File::load($id);

$groupId = getToolSessionVar('files','group');
if ($groupId>0) {
	$group = FileGroup::load($groupId);
	$parent=$group->getTitle();
}
else {
	$parent='Bibliotek';
}
$close = getToolSessionVar('files','baseWindow');


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center">'.
'<sheet width="300" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du virkelig slette filen?</title>'.
'<description>Handlingen kan ikke fortrydes og filen fjernes fra alle sider.</description>'.
($groupId>0 ? '<description>Du kan også vælge kun at fjerne filen fra denne gruppe men beholde den i biblioteket.</description>' : '').
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();" style="Hilited"/>'.
($groupId>0 ? '<button title="Fjern fra gruppe" link="RemoveFromGroup.php?id='.$id.'"/>' : '').
'<button title="Slet fra bibliotek" link="DeleteFile.php?id='.$id.'"/>'.
'</buttongroup>'.
'</message>'.
'</sheet>'.
'<parent title="'.encodeXML($parent).'" link="'.$close.'"/>';
$gui.='<titlebar title="'.encodeXML($file->getTitle()).'" icon="'.GuiUtils::getFileIcon($file->getFilename()).'">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Luk" icon="Basic/Close" link="'.$close.'"/>'.
'<divider/>'.
'<tool title="Slet" icon="Basic/Delete" link="javascript: ConfirmDelete.show();"/>'.
'<tool title="Download" icon="'.GuiUtils::getFileIcon($file->getFilename()).'" overlay="Download" link="DownloadFile.php?id='.$id.'"/>'.
'<tool title="Erstat" icon="'.GuiUtils::getFileIcon($file->getFilename()).'" overlay="Add" link="ReplaceFile.php?id='.$id.'"/>'.
'<flexible/>'.
'<tool title="Info" icon="Basic/Info" link="FileInfo.php?id='.$id.'"/>'.
'<tool title="Egenskaber" icon="'.GuiUtils::getFileIcon($file->getFilename()).'" overlay="Properties" selected="true"/>'.
'<tool title="Se filen" icon="Basic/View" link="FileView.php?id='.$id.'"/>'.
'</toolbar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdateFile.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%">'.
'<textfield badge="Titel:" name="title">'.encodeXML($file->getTitle()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="4">'.encodeXML($file->getNote()).'</textfield>'.
'<checkbox badge="Søgbar:" name="searchable" selected="'.($file->isSearchable() ? 'true' : 'false').'"/>'.
'<buttongroup size="Large">'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Message");
writeGui($xwg_skin,$elements,$gui);
?>