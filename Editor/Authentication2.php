<?php
/**
 * Displays the authentication dialog used to access the system
 *
 * @package OnlinePublisher
 * @subpackage Base
 * @category Interface
 */
require_once '../Config/Setup.php';
require_once 'Include/Functions.php';
require_once 'Classes/Request.php';
require_once 'Classes/In2iGui.php';
require_once 'Classes/DatabaseUtil.php';
require_once 'Classes/InternalSession.php';

if (requestPost()) {
	$page = Request::getPostInt('page');
	$username=Request::getPostString('username');
	$password=Request::getPostString('password');
	if (InternalSession::logIn($username,$password)) {
		if ($page>0) {
			header("Location: index.php?page=".$page);
		}
		else {
			header("Location: index.php");
		}
	}
	else {
		header("Location: Authentication.php?usernotfound=true&page=".$page);
	}
}
else {
	if (Request::getBoolean('logout')) {
		InternalSession::logOut();
	}
	$focusForm = true;
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
	'<meta><title>OnlinePublisher</title></meta>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="360" align="center" top="30">';
	if (!DatabaseUtil::isUpToDate()) {
		$focusForm = false;
		$gui.='<sheet width="300" object="DatabaseSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Error">'.
		'<title>Databasen er ikke korrekt</title>'.
		'<description>Databasen svarer ikke til hvad systemet forventer. Du bør logge ind i opsætningsværktøjet og opdatere databasen inden du logger ind i systemet.</description>'.
		'<buttongroup size="Large">'.
		'<button title="Ignorer" link="javascript: DatabaseSheet.hide(); document.forms[0].username.focus();"/>'.
		'<button title="Opsætning..." link="../setup/" style="Hilited" object="OK"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	}
	else if (Request::getBoolean('logout')) {
		$focusForm = false;
		$gui.='<sheet width="250" object="LogOutSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Message">'.
		'<title>Du er blevet logget ud</title>'.
		'<description>Du er nu blevet logget ud af systemet og du må logge ind igen for at få adgang.</description>'.
		'<buttongroup size="Large">'.
		'<button title="OK" link="javascript: LogOutSheet.hide(); document.forms[0].username.focus();" style="Hilited" object="OK"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	}
	else if (Request::getBoolean('timeout')) {
		$focusForm = false;
		$gui.='<sheet width="250" object="TimeOutSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Time">'.
		'<title>Sessionen er udløbet</title>'.
		'<description>Da du ikke har anvendt systemet de seneste 15 minutter er du blevet logget ud automatisk.</description>'.
		'<buttongroup size="Large">'.
		'<button title="OK" link="javascript: TimeOutSheet.hide(); document.forms[0].username.focus();" style="Hilited" object="OK"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	}
	else if (Request::getBoolean('usernotfound')) {
		$focusForm = false;
		$gui.='<sheet width="300" object="NoUserSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Caution">'.
		'<title>Ukendt bruger</title>'.
		'<description>Der kunne ikke findes en bruger med det indtastede brugernavn og kodeord</description>'.
		'<buttongroup size="Large">'.
		'<button title="Glemt kodeord" link="LostPassword.php" help="Klik her for at få hjælp til at genfinde dit kodeord"/>'.
		'<button title="OK" link="javascript: NoUserSheet.hide(); document.forms[0].username.focus();" style="Hilited" object="OK"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	}
	else if (Request::getBoolean('emailsent')) {
		$focusForm = false;
		$gui.='<sheet width="300" object="EmailSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Message">'.
		'<title>E-mail afsendt</title>'.
		'<description>Vi har nu afsendt en E-mail med instruktioner i hvordan kodeordet ændres.</description>'.
		'<description>Hvis ikke du modtager E-mailen bør du kontakte administratoren.</description>'.
		'<description>Af sikkerhedsmæssige årsager er instruktionerne kun gældende inden for den næste time.</description>'.
		'<buttongroup size="Large">'.
		'<button title="OK" link="javascript: EmailSheet.hide(); document.forms[0].username.focus();" style="Hilited" object="OK"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	}
	else if (Request::getBoolean('passwordchanged')) {
		$focusForm = false;
		$gui.='<sheet width="300" object="EmailSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Message">'.
		'<title>Kodeordet er nu ændret</title>'.
		'<description>Dit kodeord er nu blevet ændret og du kan anvende det til at logge ind i systemet.</description>'.
		'<buttongroup size="Large">'.
		'<button title="OK" link="javascript: EmailSheet.hide(); document.forms[0].username.focus();" style="Hilited" object="OK"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	}
	$gui.='<sheet width="270" object="In2iSoftSheet" visible="false">'.
	'<html xmlns="uri:Html"><img src="Resources/Logo.gif" width="232" height="196" border="0" onclick="In2iSoftSheet.hide();" style="cursor: pointer; margin: 10px;"/></html>'.
	'<message xmlns="uri:Message">'.
	'<title>OnlinePublisher</title>'.
	'<description>Denne applikation er designet og udviklet af In2iSoft I/S 2005</description>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="javascript: In2iSoftSheet.hide();Username.focus();" object="VisitCancel"/>'.
	'<button title="Besøg In2iSoft" link="http://www.in2isoft.dk/" target="_blank" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>'.
	'</sheet>';
	$gui.=
	'<titlebar title="Adgangskontrol">'.
	'<close link="../" help="Klik her for at vende tilbage til hjemmesiden"/>'.
	'</titlebar>'.
	'<content background="true">'.
	'<layout xmlns="uri:Layout" width="100%" spacing="8">'.
	'<row><cell valign="top"><html xmlns="uri:Html"><img src="Resources/LoginLogo.png" width="110" height="110" border="0" onclick="In2iSoftSheet.show();VisitCancel.focus();" style="cursor: pointer;"/></html></cell>'.
	'<cell valign="top">'.
	'<form xmlns="uri:Form" action="Authentication.php" method="post" submit="true"'.($focusForm ? ' focus="username"' : '').' name="Formula">'.
	'<validation>
	if (Username.isEmpty()) {
		Username.focus();
		Username.setError("Skal udfyldes!");
		Password.setError("");
		Username.blinkError(1000);
		return false;
	}
	else if (Password.isEmpty()) {
		Password.focus();
		Password.setError("Skal udfyldes!");
		Username.setError("");
		Password.blinkError(1000);
		return false;
	}
	return true;
	</validation>'.
	'<hidden name="page">'.Request::getInt('page').'</hidden>'.
	'<group size="Large">'.
	'<textfield badge="Brugernavn:" name="username" object="Username"></textfield>'.
	'<password badge="Kodeord:" name="password" object="Password"/>'.
	'<space/>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="../" help="Klik her for at vende tilbage til hjemmesiden"/>'.
	'<button title="Log ind" submit="true" style="Hilited" help="Klik her når du har udfyldt brugernavn og kodeord"/>'.
	'</buttongroup>'.
	'<space/>'.
	'<buttongroup size="Small">'.
	'<button title="Opsætning" link="../setup/" help="Grundlæggende opsætning af systemet"/>'.
	'<button title="Glemt kodeord" link="LostPassword.php" help="Genfind et glemt kodeord"/>'.
	'</buttongroup>'.
	'</group>'.
	'</form>'.
	'</cell>'.
	'</row>'.
	'</layout>'.
	'</content>'.
	'</window>'.
	'<html xmlns="uri:Html"><img src="Resources/Designed.gif" border="0" style="position: absolute; bottom: 5px; right: 10px;"/></html>'.
	'<script xmlns="uri:Script">if (window.top!=window.self) {window.top.location=window.self.location;}</script>';
	if (Request::getBoolean('notloggedin') && $focusForm) {
	    $gui.='<script xmlns="uri:Script">
	    try {
    	    Statusbar.blink(1000);
	    } catch (ignore) {}
    	</script>';
	}
	$gui.=
	'<script xmlns="uri:Script">
	try {
		OK.focus();
	} catch (ignore) {}
	</script>'.
	'</interface>'.
	'</xmlwebgui>';
	$elements = array("Window","Layout","Html","Message","Form","Script");
	In2iGui::display($elements,$gui);
}
?>
