<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.HTML
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10">
	<controller source="controller.js"/>
	<script>
		controller.id = '.Request::getId().';
	</script>
	<box width="800" top="10" padding="10" title="HTML">
		<formula name="formula">
			<fields labels="above">
				<field label="Titel:">
					<text-input key="title"/>
				</field>
				<field label="HTML:">
					<text-input key="html" multiline="true"/>
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