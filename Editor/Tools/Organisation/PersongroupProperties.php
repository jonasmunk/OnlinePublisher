<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Persongroup.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$id=getPersonGroup();

$group = PersonGroup::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<sheet width="350" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du slette gruppen?</title>'.
'<description>Det er kun gruppen der slettes. Personer i gruppen slettes IKKE men kan stadig findes i biblioteket.</description>'.
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();"/>'.
'<button title="Slet" link="DeletePersongroup.php?id='.$id.'" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</sheet>'.
'<titlebar title="'.StringUtils::escapeXML($group->getTitle()).'" icon="Element/Folder">'.
'<close link="Persongroup.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdatePersongroup.php" method="post" name="Formula" focus="title" submit="true">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($group->getTitle()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="6">'.StringUtils::escapeXML($group->getNote()).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Persongroup.php"/>'.
'<button title="Slet" link="javascript: ConfirmDelete.show();"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Form","Message");
writeGui($xwg_skin,$elements,$gui);
?>