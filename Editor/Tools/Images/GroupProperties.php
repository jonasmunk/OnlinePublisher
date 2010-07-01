<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Imagegroup.php';
require_once 'ImagesController.php';

$id=ImagesController::getGroupId();

$group = ImageGroup::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="'.encodeXML($group->getTitle()).'" icon="Element/Album">'.
'<close link="Group.php"/>'.
'</titlebar>'.
'<sheet width="350" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du slette albumet?</title>'.
'<description>Det er kun albumet der slettes. Billeder i albumet slettes IKKE men kan stadig findes i biblioteket.</description>'.
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();"/>'.
'<button title="Slet" link="DeleteGroup.php?id='.$id.'" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</sheet>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdateGroup.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%">'.
'<textfield badge="Titel:" name="title">'.encodeXML($group->getTitle()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="4">'.encodeXML($group->getNote()).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Group.php"/>'.
'<button title="Slet" link="javascript: ConfirmDelete.show();"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Message","Form");
writeGui($xwg_skin,$elements,$gui);
?>