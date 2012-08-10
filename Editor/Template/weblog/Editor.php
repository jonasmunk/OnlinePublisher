<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10">
	<controller source="controller.js"/>
	<script>
		controller.id = '.Request::getId().';
	</script>
	<box width="360" top="30" padding="10" title="Indstillinger til weblog">
		<formula name="formula">
			<fields labels="above">
				<field label="Titel:">
					<text-input key="title"/>
				</field>
				<field label="Skabelon til ny side:">
					<dropdown key="blueprint">
						<item title="Ingen skabelon" value="0"/>
						'.GuiUtils::buildObjectItems('pageblueprint').'
					</dropdown>
				</field>
				<field label="Grupper">
					<checkboxes key="groups">
						'.GuiUtils::buildObjectItems('webloggroup').'
					</checkboxes>
				</field>
			</fields>
			<buttons>
				<button title="Opdater" name="save" highlighted="true" disabled="true"/>
			</buttons>
		</formula>
	</box>
</gui>
';
In2iGui::render($gui);
?>