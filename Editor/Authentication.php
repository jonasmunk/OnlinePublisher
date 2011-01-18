<?php
/**
 * @package OnlinePublisher
 */
require_once '../Config/Setup.php';
require_once 'Include/Public.php';
require_once 'Classes/Request.php';
require_once 'Classes/In2iGui.php';
require_once 'Classes/DatabaseUtil.php';
require_once 'Classes/InternalSession.php';
require_once 'Classes/SystemInfo.php';
require_once 'Classes/Services/MailService.php';


if (Request::getBoolean('logout')) {
	InternalSession::logOut();
} else if (Request::getBoolean('timeout')) {
	
} else if (Request::getBoolean('usernotfound')) {
	
}

if (!DatabaseUtil::isUpToDate()) {
	// TODO warn user
}

$mailEnabled = MailService::getEnabled();

$gui='
<gui xmlns="uri:In2iGui" padding="10" title="'.SystemInfo::getTitle().'" state="login">
	<controller name="controller" source="Authentication.js"/>
	<box width="300" top="100" variant="rounded">
		<space all="10" top="5" bottom="5">
		<formula name="formula" state="login">
			<header>Adgangskontrol</header>
			<group>
				<text name="username" label="Brugernavn:"/>
				<text name="password" secret="true" label="Kodeord:"/>
				<buttons>
					<button name="cancel" title="Annuller" url="../"/>
					<button name="login" title="Log ind" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
		<formula name="recoveryForm" state="recover">
			<header>Genfind kodeord</header>
			<text><p>Skriv dit brugernavn eller e-mail, så sender vi dig en e-mail om hvordan du kan ændre din kode...</p></text>
			<group labels="above">
				<text key="nameOrMail" label="Brugernavn eller e-mail:"/>
				<buttons>
					<button name="cancel" title="Annuller" click="ui.changeState(\'login\');formula.focus()"/>
					<button title="Find" name="recover" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
		<fragment state="recoveryMessage">
			<space all="5">
				<text align="center">
					<h>Der er nu sendt en e-mail</h>
					<p>Du skulle inden længe modtage en e-mail der beskriver hvordan du kan ændre dit kodeord. Hvis du ikke har modtaget den inden ca. et kvarter bør du kontakte den ansvarlige for webstedet.</p>
				</text>
				<buttons align="center" top="5">
					<button title="OK" click="ui.changeState(\'login\');formula.focus()"/>
				</buttons>
			</space>
		</fragment>
		</space>
	</box>
	'.($mailEnabled ? '<fragment state="login"><text align="center"><p><link name="forgot">Glemt kodeord</link></p></text></fragment>' : '').'
</gui>';

In2iGui::render($gui);
?>