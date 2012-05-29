<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Special pages">
	<source url="data/ListSpecialPages.php" name="listSource"/>
	<controller source="specialpages.js"/>
	<source name="pageItems" url="../../Services/Model/Items.php?type=page"/>
	<toolbar>
		<icon icon="common/page" overlay="new" text="Tilføj speciel side" name="newSpecialPage"/>
	</toolbar>
	<overflow>
	<list name="list" source="listSource"/>
	</overflow>
	
	<window name="specialPageWindow" width="300" title="Speciel side" padding="5">
		<formula name="specialPageFormula">
			<fields>
				<field label="Side:">
					<dropdown key="pageId" source="pageItems"/>
				</field>
				<field label="Sprog:">
					<dropdown key="language">
						<item value="" title="Intet"/>
						<item value="DA" title="Dansk"/>
						<item value="EN" title="Engelsk"/>
					</dropdown>
				</field>
				<field label="Type:">
					<dropdown key="type">
						<item value="home" title="Forside"/>
					</dropdown>
				</field>
				<buttons>
					<button name="cancelSpecialPage" title="Annuller"/>
					<button name="deleteSpecialPage" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet speciel side" cancel="Nej"/>
					</button>
					<button name="saveSpecialPage" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>

</gui>';
In2iGui::render($gui);
?>