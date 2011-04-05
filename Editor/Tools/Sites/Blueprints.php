<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:In2iGui" title="Sites">
	<source url="ListBlueprints.php" name="listSource"/>
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
			<group>
				<text key="title" label="Titel:"/>
				<dropdown key="designId" label="Design:" source="designItems"/>
				<dropdown key="frameId" label="Ramme:" source="frameItems"/>
				<dropdown key="templateId" label="Skabelon:" source="templateItems"/>
				<buttons>
					<button name="cancelBlueprint" title="Annuller"/>
					<button name="deleteBlueprint" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet skabelon" cancel="Nej"/>
					</button>
					<button name="saveBlueprint" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>

</gui>';
In2iGui::render($gui);
?>