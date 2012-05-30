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
		<parameter key="templates" value="@showTemplates.value"/>
		<parameter key="tools" value="@showTools.value"/>
		<parameter key="email" value="@showEmail.value"/>
	</source>
	<structure>
		<top>
			<toolbar>
				<icon icon="common/internet" text="Nyt site" name="newSite"/>
				<icon icon="common/refresh" text="Opdatér" name="refresh"/>
				<field label="Vis skabeloner">
					<checkbox name="showTemplates"/>
				</field>
				<field label="Vis værktøjer">
					<checkbox name="showTools"/>
				</field>
				<field label="Vis e-mail">
					<checkbox name="showEmail"/>
				</field>
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
			<fields labels="above">
				<field label="Titel">
					<text-input key="title"/>
				</field>
				<field label="Adresse">
					<text-input key="url"/>
				</field>
				<buttons>
					<button name="cancelSite" title="Annuller"/>
					<button name="deleteSite" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Annuller"/>
					</button>
					<button name="saveSite" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>