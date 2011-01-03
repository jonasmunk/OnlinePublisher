<?php
/**
 * Displays interface for retrieving a lost password
 *
 * @package OnlinePublisher
 * @subpackage Base
 * @category Interface
 */
require_once '../Config/Setup.php';
require_once 'Include/Functions.php';
require_once 'Include/XmlWebGui.php';
require_once 'Classes/User.php';
require_once 'Classes/EmailUtil.php';
require_once 'Classes/Request.php';
require_once 'Classes/Utilities/StringUtils.php';

if (Request::exists('id')) {
    $id = Request::getString('id');
    $time = time();
    $sql = "select * from email_validation_session where timelimit>".Database::datetime($time)." and `unique`=".Database::text($id);
    if ($row = Database::selectFirst($sql)) {
        if (Request::exists('password1') && Request::exists('password2')) {
            processPasswordChange($row['user_id'],Request::getString('password1'));
        } else {
            displayPasswordChange($id);
        }
    } else {
		redirect("LostPassword.php?sessionexpired=true");
    }
}
else if (Request::isPost()) {
	$username = Request::getString('username');
	$sql="select * from user where username=".Database::text($username)." or email=".Database::text($username);
	$row = Database::selectFirst($sql);
	if ($row && $row['email']!='') {
		createValidationSession($row['email'],$row['object_id'],$row['username']);
		redirect("Authentication.php?emailsent=true");		
	}
	else {
		redirect("LostPassword.php?usernotfound=true");
	}
}
else {
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="300" align="center" top="30">';
	if (Request::getBoolean('usernotfound')) {
		$gui.='<sheet width="300" object="NoUserSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Caution">'.
		'<title>Ukendt bruger</title>'.
		'<description>Der kunne ikke findes en bruger med det indtastede brugernavn eller E-mailadresse</description>'.
		'<description>Bemærk at det kun er muligt at ændre kodeordet for brugere der har registreret en E-mailadresse i systemet.</description>'.
		'<description>Prøv venligst igen...</description>'.
		'<buttongroup size="Large">'.
		'<button title="OK" link="javascript: NoUserSheet.hide(); Username.focus();" style="Hilited"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	}
	elseif (Request::getBoolean('sessionexpired')) {
		$gui.='<sheet width="300" object="NoUserSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="time">'.
		'<title>Tiden er overskredet</title>'.
		'<description>Der er gået for lang tid fra at du anmodede om at ændre dit kodeord til nu.</description>'.
		'<description>Der må af sikkerhedshensyn højest gå en time fra at du anmoder om ændring til at den foretages.</description>'.
		'<description>Prøv venligst igen...</description>'.
		'<buttongroup size="Large">'.
		'<button title="OK" link="javascript: NoUserSheet.hide(); Username.focus();" style="Hilited"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';
	} else {
		$gui.='<sheet width="300" object="NoUserSheet" visible="true">'.
		'<message xmlns="uri:Message" icon="Message">'.
		'<title>Glemt kodeord</title>'.
		'<description>Hvis du har glemt dit kodeord har du nu muligheden for at angive et nyt.</description>'.
		'<description>For at ændre dit kodeord skal du om lidt indtaste dit brugernavn eller E-mailadresse.</description>'.
		'<description>Der vil derefter blive sendt en E-mail med instruktioner i hvordan du ændrer dit kodeord.</description>'.
		'<description>Klik på OK for at forsætte...</description>'.
		'<buttongroup size="Large">'.
		'<button title="OK" link="javascript: NoUserSheet.hide(); Username.focus();" focus="true" style="Hilited"/>'.
		'</buttongroup>'.
		'</message>'.
		'</sheet>';	    
	}
	$gui.=
	'<titlebar title="Glemt kodeord">'.
	'<close link="Authentication.php"/>'.
	'</titlebar>'.
	'<statusbar status="Info" text="Angiv brugernavn eller E-mailadresse"/>'.
	'<content background="true" padding="3">'.
	'<form xmlns="uri:Form" action="LostPassword.php" method="post" submit="true" name="Formula">'.
	'<validation>
	if (Username.isEmpty()) {
		Username.focus();
		Username.setError("Skal udfyldes!");
		Username.blinkError(1000);
		return false;
	}
	return true;
	</validation>'.
	'<group size="Large">'.
	'<textfield badge="Brugernavn eller E-mail:" name="username" object="Username"></textfield>'.
	'<space/>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="Authentication.php"/>'.
	'<button title="Find" submit="true" style="Hilited"/>'.
	'</buttongroup>'.
	'</group>'.
	'</form>'.
	'</content>'.
	'</window>'.
	'<script xmlns="uri:Script">if (window.top!=window.self) {window.top.location=window.self.location;}</script>'.
	'</interface>'.
	'</xmlwebgui>';
	$elements = array("Window","Layout","Html","Message","Form","Script");
	writeGui($xwg_skin,$elements,$gui);
}

function createValidationSession($email,$userId,$userName) {
    global $baseUrl;
    $unique = md5(uniqid(rand(), true));
    $limit = time() + 60*60; // 1 hour into future
    
    $sql = "insert into email_validation_session (`unique`,`user_id`,`email`,`timelimit`)".
    " values (".
    Database::text($unique).",".$userId.",".Database::text($email).",".Database::datetime($limit).
    ")";
    Database::insert($sql);
    // Create the email
    $body = "Klik på følgende link for at ændre dit kodeord til brugeren \"".$userName."\": \n".
    $baseUrl."Editor/LostPassword.php?id=".$unique;
    EmailUtil::send($email,$userName,"OnlinePublisher - ændring af kodeord",$body);

}

function displayPasswordChange($id) {
    global $xwg_skin;
    $gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
    '<configuration path="../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="300" align="center" top="30">';
	$gui.='<sheet width="300" object="InfoSheet" visible="true">'.
	'<message xmlns="uri:Message" icon="Message">'.
	'<title>Ændring af kodeord</title>'.
	'<description>Du har nu muligheden for at ændre dit kodeord til systemet.</description>'.
	'<description>Klik på OK for at fortsætte...</description>'.
	'<buttongroup size="Large">'.
	'<button title="OK" link="javascript: InfoSheet.hide(); Password1.focus();" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>'.
	'</sheet>';
	$gui.=
	'<titlebar title="Ændring af kodeord">'.
	'<close link="Authentication.php"/>'.
	'</titlebar>'.
	'<statusbar status="Info" text="Udfyld det nye kodeord i begge felter"/>'.
	'<content background="true" padding="5">'.
	'<form xmlns="uri:Form" action="LostPassword.php" method="post" submit="true" name="Formula">'.
	'<validation>
	if (Password1.isEmpty()) {
		Password2.setError("");
		Password1.focus();
		Password1.setError("Skal udfyldes!");
		Password1.blinkError(1000);
		return false;
	} else if (Password2.isEmpty()) {
		Password1.setError("");
		Password2.focus();
		Password2.setError("Skal udfyldes!");
		Password2.blinkError(1000);
		return false;
	} else if (Password1.getValue()!=Password2.getValue()) {
		Password1.setError("");
		Password2.focus();
		Password2.setError("Kodeordene er ikke ens!");
		Password2.blinkError(1000);
		return false;
	}
	return true;
	</validation>'.
	'<hidden name="id">'.StringUtils::escapeXML($id).'</hidden>'.
	'<group size="Large">'.
	'<password badge="Kodeord:" name="password1" object="Password1"/>'.
	'<password badge="Gentag kodeord:" name="password2" object="Password2"/>'.
	'<space/>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="Authentication.php"/>'.
	'<button title="Ændr kodeord" submit="true" style="Hilited"/>'.
	'</buttongroup>'.
	'</group>'.
	'</form>'.
	'</content>'.
	'</window>'.
	'<script xmlns="uri:Script">if (window.top!=window.self) {window.top.location=window.self.location;}</script>'.
	'</interface>'.
	'</xmlwebgui>';
	$elements = array("Window","Layout","Html","Message","Form","Script");
	writeGui($xwg_skin,$elements,$gui);
}

function processPasswordChange($userId,$password) {
    $user = User::load($userId);
    $user->setPassword($password);
    $user->update();
    redirect('Authentication.php?passwordchanged=true');
}
?>