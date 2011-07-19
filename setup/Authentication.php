<?php
/**
 * @package OnlinePublisher
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Classes/In2iGui.php';
require_once '../Editor/Classes/Request.php';
require_once 'Functions.php';


if (Request::getBoolean('logout')) {
	setupLogOut();
}

$gui='
<gui xmlns="uri:hui" padding="10" title="'.SystemInfo::getTitle().'">
	<controller name="controller" source="Authentication.js"/>
	<box width="300" top="100" title="OnlinePublisher opsætning">
		<fragment background="aluminum">
			<space all="10">
				<formula name="formula">
					<group>
						<text name="username" label="Brugernavn:"/>
						<text name="password" secret="true" label="Kodeord:"/>
						<buttons>
							<button name="cancel" title="Annuller" url="../Editor/"/>
							<button name="login" title="Log ind" highlighted="true" submit="true"/>
						</buttons>
					</group>
				</formula>
			</space>
		</fragment>
	</box>
</gui>';

In2iGui::render($gui);
?>