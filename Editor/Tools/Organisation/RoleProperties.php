<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Personrole.php';
require_once 'Functions.php';

$id = requestGetNumber('id');

$role = PersonRole::load($id);


$persons = GuiUtils::buildObjectOptions('person');


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<sheet width="350" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du slette rollen?</title>'.
'<description>Det er kun rollen der slettes. Personen tilknyttet rollen slettes IKKE og kan stadig findes i biblioteket.</description>'.
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();"/>'.
'<button title="Slet" link="DeletePersonrole.php?id='.$id.'" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</sheet>'.
'<titlebar title="'.encodeXML($role->getTitle()).'" icon="Role/Administrator">'.
'<close link="Roles.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdatePersonrole.php" method="post" name="Formula" focus="title" submit="true">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%">'.
'<textfield badge="Titel:" name="title">'.encodeXML($role->getTitle()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="4">'.encodeXML($role->getNote()).'</textfield>'.
'<select badge="Person:" name="personid" selected="'.$role->getPersonId().'">'.
'<option title="" value="0"/>'.
$persons.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Roles.php"/>'.
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