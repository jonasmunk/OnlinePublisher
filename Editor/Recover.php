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
require_once 'Classes/Utilities/StringUtils.php';
require_once 'Classes/Services/MailService.php';

$error = null;
$key = Request::getString('key');
if (StringUtils::isBlank($key)) {
	$error = array('title'=>'Ukendt forespørgsel','text'=>'Dette kan skyldes at din e-mail-klient har afskåret linket. Prøv at kopiere adressen fra e-mailen ind i browserens adresselinje.');
} else if (!AuthenticationService::isValidEmailValidationSession($key)) {
	$error = array('title'=>'Tiden er udløbet eller linket er allerede anvendt','text'=>'Af sikkerhedsmæssige grunde er der en begrænset periode du kan anvende linket i e-mailen og det kan kun anvendes een gang. Prøv venligst igen ved at lave en ny forespørgelse.');
}

$gui='
<gui xmlns="uri:hui" padding="10" title="'.SystemInfo::getTitle().'" state="login">
	'.(!$error ? '
	<controller name="controller" source="Recover.js"/>
	' : '').'
	<box width="300" top="100" variant="rounded">
		<space all="10" top="5" bottom="5">
		'.($error ? '
			<text>
				<h>'.$error['title'].'</h>
				<p>'.$error['text'].'</p>
			</text>
			<buttons align="right" top="5">
				<button highlighted="true" title="OK" url="Authentication.php"/>
			</buttons>
		' : '
			<formula name="formula" state="login">
				<header>Vælg ny kode</header>
				<fields labels="above">
					<field label="Kodeord:">
						<text-input name="password1" secret="true"/>
					</field>
					<field label="Kodeord (gentag):">
						<text-input name="password2" secret="true"/>
					</field>
					<buttons>
						<button name="cancel" title="Annuller" url="Authentication.php"/>
						<button name="change" title="Skift kode" highlighted="true" submit="true"/>
					</buttons>
				</fields>
			</formula>
			<fragment state="success">
				<text>
					<h>Dit kodeord er nu ændret</h>
					<p>Du burde nu kunne logge ind i systemet...</p>
				</text>
				<buttons align="right" top="5">
					<button highlighted="true" title="Gå til godkendelse..." url="Authentication.php"/>
				</buttons>
			</fragment>
		').'
		</space>
	</box>
	'.($mailEnabled ? '<text align="center"><p><link name="forgot">Glemt kodeord</link></p></text>' : '').'
</gui>';

In2iGui::render($gui);
?>