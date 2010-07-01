<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui=
'<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Oprettelse af ny bruger" icon="Element/User">'.
'<close link="index.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateUser.php" method="post" name="Formula" focus="fullname">'.
'<group size="Large" badgewidth="30%">'.
'<textfield badge="Navn:" name="fullname"/>'.
'<textfield badge="Brugernavn:" name="username"/>'.
'<password badge="Kodeord:" name="password"/>'.
'<textfield badge="E-mail:" name="email"/>'.
'<textfield badge="Notat:" name="note" lines="6"/>'.
'<checkbox badge="Intern adgang" name="internal" selected="true"/>'.
'<checkbox badge="Ekstern adgang" name="external" selected="false"/>'.
'<checkbox badge="Administrator" name="administrator" selected="false"/>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="index.php"/>'.
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