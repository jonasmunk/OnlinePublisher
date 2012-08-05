<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';


$gui = '
<gui xmlns="uri:hui" padding="10" title="System" state="list">
	<controller source="controller.js"/>
	<source name="listSource" url="List.php"/>
	<box width="500" top="30" title="{Changed items; da:Ændret indhold}">
		<toolbar center="true">
			<icon icon="common/internet" title="{Publish all; da:Udgiv alt}" overlay="upload" name="publishAll"/>
		</toolbar>
		<overflow height="200">
		<list name="list" source="listSource"/>
		</overflow>
	</box>
</gui>
';

In2iGui::render($gui);
?>