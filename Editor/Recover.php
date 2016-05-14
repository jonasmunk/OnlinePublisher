<?php
/**
 * @package OnlinePublisher
 */
require_once 'Include/Public.php';

if (Request::exists('language')) {
	InternalSession::setLanguage(Request::getString('language'));
}

$error = null;

$key = Request::getString('key');
if (Strings::isBlank($key)) {
	$error = array(
		'title' => array('Illegal request','da'=>'Ukendt forespørgsel'),
		'text' => 'Dette kan skyldes at din e-mail-klient har afskåret linket. Prøv at kopiere adressen fra e-mailen ind i browserens adresselinje.'
	);
} else if (!AuthenticationService::isValidEmailValidationSession($key)) {
	$error = array(
		'title' => array('The link has expired or has already been used','da'=>'Tiden er udløbet eller linket er allerede anvendt'),
		'text' => array('For security reasons there is a limited time you can use the link from the e-mail and it can only be used one time. Please try making a new request.','da'=>'Af sikkerhedsmæssige grunde er der en begrænset periode du kan anvende linket i e-mailen og det kan kun anvendes een gang. Prøv venligst igen ved at lave en ny forespørgelse.')
	);
}

$gui='
<gui xmlns="uri:hui" padding="10" title="'.SystemInfo::getTitle().'" state="login">
	<controller name="controller" source="Recover.js"/>
	<box width="300" top="100" variant="rounded">
		<space all="10" top="5" bottom="5">
		'.($error ? '
			<text>
				<h>'.GuiUtils::getTranslated($error['title']).'</h>
				<p>'.GuiUtils::getTranslated($error['text']).'</p>
			</text>
			<buttons align="right" top="5">
				<button highlighted="true" title="OK" url="Authentication.php"/>
			</buttons>
		' : '
			<formula name="formula" state="login">
				<header>{Provide new password; da:Angiv ny kode}</header>
				<fields labels="above">
					<field label="{Password; da:Kodeord}:">
						<text-input name="password1" secret="true"/>
					</field>
					<field label="{Password (again); da:Kodeord (gentag)}:">
						<text-input name="password2" secret="true"/>
					</field>
					<buttons>
						<button name="cancel" title="{Cancel; da:Annuller}" url="Authentication.php"/>
						<button name="change" title="{Change password; da:Skift kode}" highlighted="true" submit="true"/>
					</buttons>
				</fields>
			</formula>
			<fragment state="success">
				<text>
					<h>{Your password has been changed; da:Dit kodeord er nu ændret}</h>
					<p>{You should now be able to log into the system...; da:Du burde nu kunne logge ind i systemet...}</p>
				</text>
				<buttons align="right" top="5">
					<button highlighted="true" title="{Log in...; da;Log ind...}" url="Authentication.php"/>
				</buttons>
			</fragment>
		').'
		</space>
	</box>
	<text align="center">
		<p><link name="english">English</link> · <link name="danish">Dansk</link></p>
	</text>
</gui>';

UI::render($gui);
?>