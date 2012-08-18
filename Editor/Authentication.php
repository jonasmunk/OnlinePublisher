<?php
/**
 * @package OnlinePublisher
 */
require_once 'Include/Public.php';

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

if (Request::exists('language')) {
	InternalSession::setLanguage(Request::getString('language'));
}


$gui='
<gui xmlns="uri:hui" padding="10" title="'.SystemInfo::getTitle().'" state="'.$state.'">
	<controller name="controller" source="Authentication.js"/>
	<box width="300" top="100" variant="rounded" name="box">
		<space all="10" top="5" bottom="5">
			<formula name="formula" state="login">
				<header>{Access control; da:Adgangskontrol}</header>
				<fields>
					<field label="{Username; da:Brugernavn}:">
						<text-input name="username" correction="false"/>
					</field>
					<field label="{Password; da:Kodeord}:">
						<text-input name="password" secret="true"/>
					</field>
					<buttons>
						<button name="cancel" title="{Cancel; da:Annuller}" url="../"/>
						<button name="login" title="{Log in; da:Log ind}" highlighted="true" submit="true"/>
					</buttons>
				</fields>
			</formula>
			<formula name="recoveryForm" state="recover">
				'.($mailEnabled ? '
				<header>{Recover password; da:Genfind kodeord}</header>
				<text><p>{Please provide your username or e-mail and we will send you an e-mail describing how you can change your password...; da:Skriv dit brugernavn eller e-mail, så sender vi dig en e-mail om hvordan du kan ændre din kode...}</p></text>
				<fields labels="above">
					<field label="{Username or e-mail; da:Brugernavn eller e-mail}:">
						<text-input key="nameOrMail"/>
					</field>
					<buttons>
						<button title="{New user; da:Ny bruger}" name="createAdmin"/>
						<button name="cancel" title="{Cancel; da:Annuller}" click="hui.ui.changeState(\'login\');formula.focus()"/>
						<button title="Find" name="recover" highlighted="true" submit="true"/>
					</buttons>
				</fields>
				' : '
				<header>{Recover password; da:Genfind kodeord}</header>
				<text>
					<p>{The system is not configured to send e-mails. Please contact the responsible for the system in order to get access; da:Systemet er ikke konfigureret til at sende e-mail. Kontakt den ansvarlige for systemet for at få adgang.}</p>
					<p>{You can create a new administrator if you know the super user.; da:Du kan også oprette en ny administrator hvis du kender super-brugeren.}</p>
				</text>
				<fields labels="above">
					<buttons>
						<button name="cancel" title="{Cancel; da:Annuller}" click="hui.ui.changeState(\'login\');formula.focus()"/>
						<button title="{New user; da:Ny bruger}" name="createAdmin"/>
					</buttons>
				</fields>				
				'
				).'
			</formula>
			<fragment state="recoveryMessage">
				<space all="5">
					<text align="center">
						<h>{An e-mail has been sent; da:Der er nu sendt en e-mail}</h>
						<p>{You should in a short time receive an e-mail describing how you can change your password. If you do not receive the e-mail within 15 min. then please contact the responsible for the website.; da:Du skulle inden længe modtage en e-mail der beskriver hvordan du kan ændre dit kodeord. Hvis du ikke har modtaget den inden ca. et kvarter bør du kontakte den ansvarlige for webstedet.}</p>
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
	<fragment state="login">
		<text align="center">
			<p><link name="forgot">{Forgot password?; da:Glemt kodeord?}</link></p>
			<p><link name="english">English</link> <link name="danish">Dansk</link></p>
		</text>
	</fragment>

	<window name="databaseWindow" width="300" padding="5" title="{Update database;da:Opdatér databasen}">
		<formula name="databaseFormula">
			<text align="center">
				<p>{da:Log ind med super-bruger for at opdatere databasen;en:Log in as super-user in order to update the database}</p>
			</text>
			<fields>
				<field label="{Username;da:Brugernavn}">
					<text-input key="username"/>
				</field>
				<field label="{Password;da:Kodeord}">
					<text-input key="password" secret="true"/>
				</field>
			</fields>
			<buttons>
				<button text="{Update;da:Opdater}" submit="true" highlighted="true"/>
			</buttons>
		</formula>
	</window>

	<window title="Log" name="databaseLogWindow" width="500">
		<text-input adaptive="true" multiline="true" name="databaseLog"/>
	</window>

	<window name="adminWindow" width="300" padding="10" title="{Create administrator; da: Opret administrator}">
		<formula name="adminFormula">
			<text align="center">
				<p>{da:Log ind med super-brugeren for at oprette en ny administrator;en:Log in as the super-user in order to create a new administrator}</p>
			</text>
			<fieldset legend="{Super user ; da: Super-bruger}">
				<fields>
					<field label="{Username;da:Brugernavn}">
						<text-input key="superUsername"/>
					</field>
					<field label="{Password;da:Kodeord}">
						<text-input key="superPassword" secret="true"/>
					</field>
				</fields>
			</fieldset>
			<space height="10"/>
			<fieldset legend="Administrator">
				<fields>
					<field label="{Username;da:Brugernavn}">
						<text-input key="adminUsername"/>
					</field>
					<field label="{Password;da:Kodeord}">
						<text-input key="adminPassword" secret="true"/>
					</field>
				</fields>
			</fieldset>
			<buttons top="10">
				<button text="{Create;da:Opret}" submit="true" highlighted="true"/>
			</buttons>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>