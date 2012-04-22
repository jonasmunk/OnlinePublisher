<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Central
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System">
	<controller source="controller.js"/>
	<source name="listSource" url="data/List.php">
		<parameter key="subset" value="@selector.value"/>
	</source>
	<structure>
		<top>
			<toolbar>
				<icon icon="common/internet" text="Nyt site" name="newSite"/>
			</toolbar>
		</top>
		<middle>
			<left>
				<selection value="all" name="selector">
					<item icon="common/folder" title="Alle" value="all"/>
				</selection>
			</left>
			<center>
				<overflow>
					<list name="list" source="listSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<window title="Site" name="siteWindow" width="300" padding="5">
		<formula name="siteFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<text label="Adresse" key="url"/>
				<buttons>
					<button name="cancelSite" title="Annuller"/>
					<button name="deleteSite" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Annuller"/>
					</button>
					<button name="saveSite" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>