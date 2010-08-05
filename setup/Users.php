<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<text align="center" top="15" bottom="15" xmlns="uri:Text">'.
'<strong>Oprettelse af en ny administrator</strong>'.
'<break/>'.
'<small>Her kan du oprette en ny bruger med fuld adgang til systemet.'.
'<break/>'.
'Den egentlige opsætning af brugere foregår med værktøjet "Brugere" inde i systemet.'.
'<break/><strong>Bemærk:</strong> Du kan oprette så mange administratore som du vil.'.
'</small>'.
'</text>'.
'<layout width="100%" xmlns="uri:Layout">'.
'<row>'.
'<cell width="20%"/>'.
'<cell width="60%">'.
'<form action="UsersCreateAdmin.php" method="post" xmlns="uri:Form">'.
'<validation>
var ok = true;
if (Fullname.isEmpty()) {
	Fullname.setError("Skal udfyldes");
	Fullname.focus();
	ok=false;
}
else if (Username.isEmpty()) {
	Username.setError("Skal udfyldes");
	Username.focus();
	ok=false;
}
else if (Password.isEmpty()) {
	Password.setError("Skal udfyldes");
	Password.focus();
	ok=false;
}
return ok;
</validation>'.
'<group size="Large">'.
'<box title="Opret ny administrator">'.
'<textfield badge="Navn:" name="fullname" object="Fullname">Administrator</textfield>'.
'<textfield badge="Brugernavn:" name="username" object="Username"/>'.
'<password badge="Kodeord:" name="password" object="Password"/>'.
'<space/>'.
'<buttongroup size="Large">'.
'<button title="Opret" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</box>'.
'</group>'.
'</form>'.
'</cell>'.
'<cell width="20%"/>'.
'</row></layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Form","Layout","Text");
writeGui($xwg_skin,$elements,$gui);
?>