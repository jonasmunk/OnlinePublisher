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
	<box width="360" top="30" padding="10" title="Indstillinger til weblog">
		<formula name="formula">
			<group labels="above">
				<text key="title" label="Titel:"/>
				<dropdown key="blueprint" label="Skabelon til ny side:">
					<item title="Ingen skabelon" value="0"/>
					'.GuiUtils::buildObjectItems('pageblueprint').'
				</dropdown>
				<checkboxes key="groups" label="Grupper">
					'.GuiUtils::buildObjectItems('webloggroup').'
				</checkboxes>
				<buttons>
					<button title="Opdater" name="save" highlighted="true" disabled="true"/>
				</buttons>
			</group>
		</formula>
	</box>
</gui>
';
In2iGui::render($gui);
?>