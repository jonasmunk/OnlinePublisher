<?php
/**
 * @package OnlinePublisher
 */
require_once '../Config/Setup.php';
require_once 'Include/Public.php';
require_once 'Classes/Core/Request.php';
require_once 'Classes/Interface/In2iGui.php';
require_once 'Classes/Utilities/DatabaseUtil.php';
require_once 'Classes/Core/InternalSession.php';
require_once 'Classes/Core/SystemInfo.php';
require_once 'Classes/Services/MailService.php';



if (Request::getBoolean('logout')) {
	InternalSession::logOut();
}

if (!Database::testConnection()) {
	$state = 'noConnection';
}
else if (!DatabaseUtil::isUpToDate()) {
	$state = 'databaseWarning';
} else {
	$state = 'login';
}
if (Database::testConnection()) {
	$mailEnabled = MailService::getEnabled();
} else {
	$mailEnabled = false;
}


$gui='
<gui xmlns="uri:hui" padding="10" title="'.SystemInfo::getTitle().'" state="'.$state.'">
	<controller name="controller" source="Authentication.js"/>
	<box width="300" top="100" variant="rounded" name="box">
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
				'.($mailEnabled ? '
				<header>Genfind kodeord</header>
				<text><p>Skriv dit brugernavn eller e-mail, så sender vi dig en e-mail om hvordan du kan ændre din kode...</p></text>
				<group labels="above">
					<text key="nameOrMail" label="Brugernavn eller e-mail:"/>
					<buttons>
						<button title="Ny bruger" name="createAdmin"/>
						<button name="cancel" title="Annuller" click="hui.ui.changeState(\'login\');formula.focus()"/>
						<button title="Find" name="recover" highlighted="true" submit="true"/>
					</buttons>
				</group>
				' : '
				<header>Genfind kodeord</header>
				<text>
					<p>Systemet er ikke konfigureret til at sende e-mail. Kontakt den ansvarlige for systemet for at få adgang.</p>
					<p>Du kan også oprette en ny administrator hvis du kender super-brugeren.</p>
				</text>
				<group labels="above">
					<buttons>
						<button name="cancel" title="Annuller" click="hui.ui.changeState(\'login\');formula.focus()"/>
						<button title="Ny bruger" name="createAdmin"/>
					</buttons>
				</group>				
				'
				).'
			</formula>
			<fragment state="recoveryMessage">
				<space all="5">
					<text align="center">
						<h>Der er nu sendt en e-mail</h>
						<p>Du skulle inden længe modtage en e-mail der beskriver hvordan du kan ændre dit kodeord. Hvis du ikke har modtaget den inden ca. et kvarter bør du kontakte den ansvarlige for webstedet.</p>
					</text>
					<buttons align="center" top="5">
						<button title="OK" click="hui.ui.changeState(\'login\');formula.focus()"/>
					</buttons>
				</space>
			</fragment>
			<fragment state="databaseWarning">
				<space all="5">
					<text align="center">
						<h>{da:Databasen er ikke korrekt;en:The database is not correct}</h>
						<p>{da:Systemet vil være ustabilt indtil databasen opdateres; en:The system will be unstable until the database is updated}</p>
						<p>{da:Du kan opdatere databasen hvis du kender super-brugeren; en:You can update the database if you know the super user}</p>
					</text>
					<buttons align="center" top="5">
						<button title="{Sign in;da:Log ind}" click="hui.ui.changeState(\'login\');formula.focus()"/>
						<button title="{Update;da:Opdater}" name="updateDatabase" highlighted="true"/>
					</buttons>
				</space>
			</fragment>
			<fragment state="noConnection">
				<space all="5">
					<text align="center">
						<h>{da:Databasen kan ikke tilgås;en:The database Cannot be reached}</h>
						<p>{da:Kontroller venligst at systemet er konfigureret korrekt og at databasen kører; en:Please make sure the system i configured correctly and the database is running}</p>
					</text>
				</space>
			</fragment>
		</space>
	</box>
	<fragment state="login"><text align="center"><p><link name="forgot">Glemt kodeord</link></p></text></fragment>

	<window name="databaseWindow" width="300" padding="5" title="{Update database;da:Opdatér databasen}">
		<formula name="databaseFormula">
			<text align="center">
				<p>{da:Log ind med super-bruger for at opdatere databasen;en:Log in as super-user in order to update the database}</p>
			</text>
			<group>
				<text label="{Username;da:Brugernavn}" key="username"/>
				<text label="{Password;da:Kodeord}" key="password" secret="true"/>
			</group>
			<buttons>
				<button text="{Update;da:Opdater}" submit="true" highlighted="true"/>
			</buttons>
		</formula>
	</window>

	<window title="Log" name="databaseLogWindow" width="500">
		<textfield adaptive="true" multiline="true" name="databaseLog"/>
	</window>

	<window name="adminWindow" width="300" padding="10" title="{Create administrator; da: Opret administrator}">
		<formula name="adminFormula">
			<text align="center">
				<p>{da:Log ind med super-brugeren for at oprette en ny administrator;en:Log in as the super-user in order to create a new administrator}</p>
			</text>
			<fieldset legend="{Super user ; da: Super-bruger}">
				<group>
					<text label="{Username;da:Brugernavn}" key="superUsername"/>
					<text label="{Password;da:Kodeord}" key="superPassword" secret="true"/>
				</group>
			</fieldset>
			<space height="10"/>
			<fieldset legend="Administrator">
				<group>
					<text label="{Username;da:Brugernavn}" key="adminUsername"/>
					<text label="{Password;da:Kodeord}" key="adminPassword" secret="true"/>
				</group>
			</fieldset>
			<buttons top="10">
				<button text="{Create;da:Opret}" submit="true" highlighted="true"/>
			</buttons>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>