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
			<overflow max-height="200">
			';
			foreach (SearchTemplate::$TYPES as $key => $label) {
				$gui.='
				<space height="10"/>
				<fieldset legend="'.$label.'">
					<fields>
						<field label="Tekst">
							<text-input key="'.$key.'Label"/>
						</field>
						<field label="Aktiv">
							<checkbox key="'.$key.'Enabled"/>
						</field>
						<field label="Skjult">
							<checkbox key="'.$key.'Hidden"/>
						</field>
						<field label="Valgt">
							<checkbox key="'.$key.'Default"/>
						</field>
					</fields>
				</fieldset>';
			}
			$gui.='
			</overflow>
		</formula>
	</box>
</gui>
';
UI::render($gui);
?>