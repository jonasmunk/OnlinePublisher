<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Blueprints">
	<source url="data/ListBlueprints.php" name="listSource"/>
	<controller source="blueprints.js"/>
	<source name="designItems" url="../../Services/Model/Items.php?type=design"/>
	<source name="frameItems" url="../../Services/Model/Items.php?type=frame"/>
	<source name="templateItems" url="../../Services/Model/Items.php?type=template"/>
	<toolbar>
		<icon icon="common/page" overlay="new" text="Tilføj skabelon" name="newBlueprint"/>
	</toolbar>
	<overflow>
	<list name="list" source="listSource"/>
	</overflow>
	
	<window name="blueprintWindow" width="300" title="Skabelon" padding="5">
		<formula name="blueprintFormula">
			<fields>
				<field label="Titel:">
					<text-input key="title"/>
				</field>
				<field label="Design:">
					<dropdown key="designId" source="designItems"/>
				</field>
				<field label="Ramme:">
					<dropdown key="frameId" source="frameItems"/>
				</field>
				<field label="Skabelon:">
					<dropdown key="templateId" source="templateItems"/>
				</field>
				<buttons>
					<button name="cancelBlueprint" title="Annuller"/>
					<button name="deleteBlueprint" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet skabelon" cancel="Nej"/>
					</button>
					<button name="saveBlueprint" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>

</gui>';
In2iGui::render($gui);
?>