<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';

require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';

require_once '../../Classes/Filegroup.php';
require_once 'Functions.php';

$id = getToolSessionVar('files','group');
$group = FileGroup::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="'.encodeXML($group->getTitle()).'" icon="Element/Folder">'.
'<close link="Group.php"/>'.
'</titlebar>'.
'<sheet width="350" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Vil du slette gruppen?</title>'.
'<description>Det er kun gruppen der slettes. Filer i gruppen slettes IKKE men kan stadig findes i biblioteket.</description>'.
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
'<textfield badge="Beskrivelse:" name="description" lines="6">'.encodeXML($group->getNote()).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="javascript: ConfirmDelete.show();"/>'.
'<button title="Annuller" link="Group.php"/>'.
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