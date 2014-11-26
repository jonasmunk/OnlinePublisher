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
		<icon icon="common/page" overlay="new" text="{New template; da:Ny skabelon}" name="newBlueprint"/>
	</toolbar>
	<overflow>
	<list name="list" source="listSource"/>
	</overflow>
	
	<window name="blueprintWindow" width="300" title="{Template; da:Skabelon}" padding="5">
		<formula name="blueprintFormula">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input key="title"/>
				</field>
				<field label="Design:">
					<dropdown key="designId" source="designItems"/>
				</field>
				<field label="{Setup; da:Opsætning}:">
					<dropdown key="frameId" source="frameItems"/>
				</field>
				<field label="{Type; da:Type}:">
					<dropdown key="templateId" source="templateItems"/>
				</field>
				<buttons>
					<button name="cancelBlueprint" title="{Cancel; da:Annuller}"/>
					<button name="deleteBlueprint" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete template; da:Ja, slet skabelon}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveBlueprint" title="{Save; da:Gem}" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>

</gui>';
UI::render($gui);
?>