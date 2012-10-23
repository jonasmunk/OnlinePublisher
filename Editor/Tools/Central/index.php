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
				<icon icon="common/internet" text="{New site; da:Nyt site}" name="newSite"/>
				<icon icon="common/refresh" text="{Update; da:Opdatér}" name="refresh"/>
				<field label="{Show templates; da:Vis skabeloner}">
					<checkbox name="showTemplates"/>
				</field>
				<field label="{Show tools; da:Vis værktøjer}">
					<checkbox name="showTools"/>
				</field>
				<field label="{Show e-mail; da:Vis e-mail}">
					<checkbox name="showEmail"/>
				</field>
			</toolbar>
		</top>
		<middle>
			<left>
				<selection value="all" name="selector" top="5">
					<item icon="common/folder" title="{All; da:Alle}" value="all"/>
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
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
				<field label="{Address; da:Adresse}">
					<text-input key="url"/>
				</field>
				<buttons>
					<button name="cancelSite" title="{Cancel; da:Annuller}"/>
					<button name="deleteSite" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel; da:Annuller}"/>
					</button>
					<button name="saveSite" title="{Save; da:Gem}" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>