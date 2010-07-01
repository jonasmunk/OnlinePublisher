<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/User.php';
require_once '../../Include/XmlWebGui.php';

$id = requestGetNumber('id',0);

$user = User::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af bruger" icon="Element/User">'.
'<close link="index.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateUser.php" method="post" name="Formula" focus="fullname">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="30%">'.
'<textfield badge="Navn:" name="fullname">'.encodeXML($user->getTitle()).'</textfield>'.
'<textfield badge="Brugernavn:" name="username">'.encodeXML($user->getUsername()).'</textfield>'.
'<password badge="Kodeord:" name="password">'.encodeXML($user->getPassword()).'</password>'.
'<textfield badge="E-mail:" name="email">'.encodeXML($user->getEmail()).'</textfield>'.
'<textfield badge="Notat:" name="note" lines="6">'.encodeXML($user->getNote()).'</textfield>'.
'<checkbox badge="Intern adgang" name="internal" selected="'.($user->getInternal() ? 'true' : 'false').'"/>'.
'<checkbox badge="Ekstern adgang" name="external" selected="'.($user->getExternal() ? 'true' : 'false').'"/>'.
'<checkbox badge="Administrator" name="administrator" selected="'.($user->getAdministrator() ? 'true' : 'false').'"/>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteUser.php?id='.$id.'"/>'.
'<button title="Annuller" link="index.php"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
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