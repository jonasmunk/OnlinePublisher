<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';

if (requestPost()) {
	$username=requestPostText('username');
	$password=requestPostText('password');
	if ($username==$superUser && $password==$superPassword) {
		setupLogIn();
		header("Location: index.php");
	}
	else {
		$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
		'<interface background="Desktop">'.
		'<window xmlns="uri:Window" width="300" align="center" top="30">'.
		'<titlebar title="Opsætning"/>'.
		'<content background="Window">'.
		'<message xmlns="uri:Message" icon="Caution">'.
		'<title>Brugeren kunne ikke findes</title>'.
		'<description>Der kunne ikke findes en bruger med det indtastede brugernavn og kodeord</description>'.
		'<buttongroup size="Large">'.
		'<button title="Prøv igen..." link="Authentication.php"/>'.
		'</buttongroup>'.
		'</message>'.
		'</content>'.
		'</window>'.
		'</interface>'.
		'</xmlwebgui>';
		$elements = array("Window","Message","Html");
		writeGui($xwg_skin,$elements,$gui);
	}
}
else {
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
	'<interface background="Desktop">'.
	'<meta><title>OnlinePublisher opsætning</title></meta>'.
	'<window xmlns="uri:Window" width="300" align="center" top="30">';
	
	if (requestGetBoolean('logout')) {
		setupLogOut();
		$gui.='<sheet width="250" object="LogOutSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Message">'.
		'<title>Du er blevet logget ud</title>'.
		'<description>Du er nu blevet logget ud af opsætningen og du må logge ind igen for at få adgang.</description>'.
		'<buttongroup size="Large">'.
		'<button title="OK" link="javascript: LogOutSheet.hide(); document.forms[0].username.focus();" style="Hilited"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	}
	$gui.=
	'<titlebar title="Opsætning">'.
	'<close link="../"/>'.
	'</titlebar>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Security">'.
	'<form xmlns="uri:Form" action="Authentication.php" method="post" submit="true"'.(!requestGetBoolean('logout') ? ' focus="username"' : '').' name="Formula">'.
	'<hidden name="page">'.requestGetText('page').'</hidden>'.
	'<group size="Large">'.
	'<textfield badge="Brugernavn:" name="username"></textfield>'.
	'<password badge="Kodeord:" name="password"/>'.
	'<space/>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="../Editor/"/>'.
	'<button title="Log ind" submit="true" style="Hilited"/>'.
	'</buttongroup>'.
	'</group>'.
	'</form>'.
	'</message>'.
	'</content>'.
	'</window>'.
	'<script xmlns="uri:Script">if (window.top!=window.self) {window.top.location=window.self.location;}</script>'.
	//'<html xmlns="uri:Html"><embed src="Welcome.aif" autoplay="true" style="visibility: hidden; height: 1px; width: 1px;"/></html>'.
	'</interface>'.
	'</xmlwebgui>';
	$elements = array("Window","Message","Form","Script","Html");
	writeGui($xwg_skin,$elements,$gui);
}
?>
