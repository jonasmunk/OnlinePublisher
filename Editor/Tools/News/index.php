<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../Include/Private.php';
require_once '../../Classes/Services/FileSystemService.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());

$blueprints = PageService::getBlueprintsByTemplate('document');
$blueprintItems = GuiUtils::buildObjectItems($blueprints);

$gui='
<gui xmlns="uri:hui" title="Nyheder" padding="10">
	<controller source="controller.js"/>
	<controller source="sources.js"/>
	<source name="pageItems" url="../../Services/Model/Items.php?type=page"/>
	<source name="fileItems" url="../../Services/Model/Items.php?type=file"/>
	<source name="groupSource" url="data/GroupItems.php"/>
	<source name="sourcesSource" url="../../Services/Model/Items.php?type=newssource"/>
	<source name="newsSource" url="data/ListNews.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="group" value="@groupSelection.value"/>
		<parameter key="main" value="@selector.value"/>
		<parameter key="source" value="@sourceSelection.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="common/news" title="Ny nyhed" name="newNews" overlay="new"/>
				'.($blueprintItems ? '<icon icon="common/page" title="Ny artikel" name="newArticle" overlay="new"/>' : '').'
				<icon icon="common/folder" title="Ny gruppe" name="newGroup" overlay="new"/>
				<icon icon="common/internet" title="Ny kilde" name="newSource" overlay="new"/>
				<divider/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/delete" title="Slet" name="delete" disabled="true">
					<confirm text="Er du sikker?" ok="Ja, slet nyheden" cancel="Annuller"/>
				</icon>
				<icon icon="common/duplicate" title="Dubler" name="duplicate" disabled="true"/>
				<right>
					<searchfield title="Søgning" name="search" expandedWidth="200"/>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="all" name="selector">
					<item icon="common/files" title="Alle" value="all"/>
					<item icon="common/time" title="Seneste døgn" value="latest"/>
					<item icon="common/play" title="Aktive" value="active"/>
					<item icon="common/stop" title="Inaktive" value="inactive"/>
					<title>Links</title>
					<item icon="monochrome/globe" title="Eksterne" value="url"/>
					<item icon="monochrome/file" title="Sider" value="page"/>
					<item icon="monochrome/file" title="Filer" value="file"/>
					<item icon="monochrome/email" title="E-mail" value="email"/>
					<items source="groupSource" name="groupSelection" title="Grupper"/>
					<items source="sourcesSource" name="sourceSelection" title="Kilder"/>
				</selection>
				</overflow>
			</left>
			<center>
				<bar variant="layout" state="source">
					<text name="sourceHeader" variant="header"/>
					<text name="sourceText"/>
					<right>
						<button text="Info" name="sourceInfo" small="true" rounded="true"/>
						<button text="Synkroniser" name="synchronize" small="true" rounded="true"/>
					</right>
				</bar>
				<bar variant="layout" state="group">
					<text name="groupHeader" variant="header"/>
					<right>
						<button text="Info" name="groupInfo" small="true" rounded="true"/>
						<button text="RSS-url" name="groupRSS" small="true" rounded="true"/>
					</right>
				</bar>
				<overflow>
					<list name="list" source="newsSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</layout>
	
	<window title="Gruppe" name="groupWindow" icon="common/folder" width="300" padding="5">
		<formula name="groupFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<buttons>
					<button name="cancelGroup" title="Annuller"/>
					<button name="deleteGroup" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet gruppen" cancel="Nej"/>
					</button>
					<button name="saveGroup" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>

	<window title="Kilde" name="sourceWindow" icon="common/internet" width="300" padding="5">
		<formula name="sourceFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<text label="Adresse" key="url"/>
				<number label="Interval (sekunder)" key="syncInterval"/>
				<buttons>
					<button name="cancelSource" title="Annuller"/>
					<button name="deleteSource" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet kilden" cancel="Nej"/>
					</button>
					<button submit="true" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	
	<window title="Nyhed" name="newsWindow" variant="news" icon="common/news" width="450">
	
		<tabs small="true" centered="true">
		
			<tab title="Nyhed" padding="10">
				<formula name="newsFormula">
					<group labels="above">
						<text label="Titel" key="title"/>
						<text label="Note" key="note" multiline="true"/>
					</group>
					<columns space="10">
						<column>
							<group labels="above">
								<datetime label="Fra" key="startdate"/>
							</group>
						</column>
						<column>
							<group labels="above">
								<datetime label="Til" key="enddate"/>
							</group>
						</column>
					</columns>
					<group labels="above">
						<checkboxes label="Grupper:" name="newsGroups">
							<items source="groupSource"/>
						</checkboxes>
					</group>
				</formula>
			</tab>
			
			<tab title="Links">
				<toolbar centered="true">
					<icon title="Tilføj link" icon="common/link" overlay="new" click="newsLinks.addLink()"/>
				</toolbar>
				<links name="newsLinks" pageSource="pageItems" fileSource="fileItems"/>
			</tab>
			
		</tabs>
		
		<buttons right="10" bottom="5" align="right">
			<button name="cancelNews" title="Annuller"/>
			<button name="deleteNews" title="Slet">
				<confirm text="Er du sikker?" ok="Ja, slet nyheden" cancel="Annuller"/>
			</button>
			<button name="updateNews" title="Gem" highlighted="true"/>
		</buttons>
		
	</window>
	
	
	<box title="Ny artikel" name="newArticleBox" absolute="true" padding="10" modal="true" width="636" variant="textured" closable="true">
		<formula name="articleFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<text label="Opsummering" key="summary" multiline="true"/>
				<text label="Tekst" key="text" multiline="true"/>
				<text label="Link" key="linkText"/>
				<dropdown label="Skabelon" key="blueprint" name="articleBlueprint">'.$blueprintItems.'</dropdown>
			</group>
			<columns>
				<column>
				<group labels="above">
					<datetime label="Fra" key="startdate"/>
				</group>
				</column>
				<column>
				<group labels="above">
					<datetime label="Til" key="enddate"/>
				</group>
				</column>
			</columns>
			<group labels="above">
				<checkboxes label="Grupper:" key="groups">
					<items source="groupSource"/>
				</checkboxes>
			</group>
			<buttons>
				<button name="cancelNewArticle" title="Annuller" click="newNewsBox.hide()"/>
				<button name="createNewArticle" title="Opret" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</box>
</gui>';

In2iGui::render($gui);
?>