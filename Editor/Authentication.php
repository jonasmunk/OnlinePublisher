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


if (Request::getBoolean('logout')) {
	InternalSession::logOut();
} else if (Request::getBoolean('timeout')) {
	
} else if (Request::getBoolean('usernotfound')) {
	
}

if (!DatabaseUtil::isUpToDate()) {
	// TODO warn user
}

$gui='
<gui xmlns="uri:In2iGui" padding="10" title="'.SystemInfo::getTitle().'">
	<controller name="controller" source="Authentication.js"/>
	<box width="300" top="100" variant="rounded">
		<space all="10" top="5" bottom="5">
		<formula name="formula">
			<header>OnlinePublisher login</header>
			<group>
				<text name="username" label="Brugernavn:"/>
				<text name="password" secret="true" label="Kodeord:"/>
				<buttons>
					<button name="cancel" title="Annuller" url="../"/>
					<button name="login" title="Log ind" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
		</space>
	</box>
</gui>';

In2iGui::render($gui);
?>