<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

$gui='
<gui xmlns="uri:hui" padding="10">
	<controller source="controller.js"/>
	<script>
		controller.id = '.Request::getId().';
	</script>
	<box width="360" top="30" padding="10" title="Indstillinger til søgeside">
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
			<fieldset legend="Sider">
				<fields>
				<field label="Opførsel">
					<dropdown value="inactive" key="pagesState">
						<item value="inactive" text="Inaktiv"/>
						<item value="possible" text="Kan vælges"/>
						<item value="active" text="Altid aktiv"/>
						<item value="automatic" text="Valgt på forhånd"/>
					</dropdown>
				</field>
				<field label="Tekst">
					<text-input key="pagesLabel"/>
				</field>
				</fields>
			</fieldset>
		</formula>
	</box>
</gui>
';
In2iGui::render($gui);
?>