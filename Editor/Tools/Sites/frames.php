<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Special pages">
	<source url="data/ListFrames.php" name="listSource"/>
	<controller source="js/frames.js"/>
	<source name="pageItems" url="../../Services/Model/Items.php?type=page"/>
	<source name="fileItems" url="../../Services/Model/Items.php?type=file"/>
	<source name="hierarchyItems" url="data/HierarchyItems.php"/>
	<toolbar>
		<icon icon="common/page" overlay="new" text="Tilføj ramme" name="newFrame"/>
	</toolbar>
	<overflow>
		<list name="list" source="listSource"/>
	</overflow>
	
	<window name="frameWindow" width="300" title="Ramme">
		<tabs small="true" centered="true">
			<tab title="Info" padding="5">
				<formula name="frameFormula">
					<group>
						<text label="Titel:" key="name"/>
						<text label="Sidetitel:" key="title"/>
						<text label="Bund-tekst:" key="bottomText" multiline="true"/>
						<dropdown key="hierarchyId" label="Hierarki:" source="hierarchyItems" placeholder="Vælg..."/>
					</group>
				</formula>
			</tab>
			<tab title="Top-links">
				<toolbar centered="true">
					<icon title="Tilføj link" icon="common/link" overlay="new" click="topLinks.addLink()"/>
				</toolbar>
				<links name="topLinks" pageSource="pageItems" fileSource="fileItems"/>
			</tab>
			<tab title="Bund-links">
				<toolbar centered="true">
					<icon title="Tilføj link" icon="common/link" overlay="new" click="bottomLinks.addLink()"/>
				</toolbar>
				<links name="bottomLinks" pageSource="pageItems" fileSource="fileItems"/>
			</tab>
		</tabs>
		<space all="5">
		<buttons align="right">
			<button name="cancelFrame" title="Annuller"/>
			<button name="deleteFrame" title="Slet">
				<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
			</button>
			<button name="saveFrame" title="Gem" highlighted="true"/>
		</buttons>
		</space>
	</window>

</gui>';
In2iGui::render($gui);
?>