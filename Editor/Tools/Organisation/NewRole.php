<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/GuiUtils.php';

require_once 'Functions.php';

$persons = GuiUtils::buildObjectOptions('person');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Ny rolle" icon="Role/Administrator">'.
'<close link="Roles.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreatePersonrole.php" method="post" name="Formula" focus="title">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<textfield badge="Beskrivelse:" name="description" lines="6"/>'.
'<select badge="Person:" name="personid" hint="Personen med rollen">'.
'<option title="" value="0"/>'.
$persons.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Roles.php"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>