<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Guestbook
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10">
	<controller source="js/controller.js"/>
	<source name="listSource" url="data/List.php">
		<parameter key="id" value="'.Request::getId().'"/>
	</source>
	<script>
		controller.id = '.Request::getId().';
	</script>
	<box width="600" top="30" title="Indstillinger til gæstebog">
		<toolbar>
			<icon icon="common/save" text="Gem" name="save" disabled="true"/>
		</toolbar>
		<formula padding="10" name="formula">
			<fields>
				<field label="Titel">
					<text-input key="title"/>
				</field>
				<field label="Tekst">
					<text-input multiline="true" key="text"/>
				</field>
			</fields>
			<overflow max-height="200">
				<list name="list" source="listSource" variant="light"/>
			</overflow>
		</formula>
	</box>
</gui>
';
UI::render($gui);
?>