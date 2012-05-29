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
	
	<structure>
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
					<field label="Søgning">
						<searchfield name="search" expanded-width="200"/>
					</field>
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
	</structure>
	
	<window title="Gruppe" name="groupWindow" icon="common/folder" width="300" padding="5">
		<formula name="groupFormula">
			<group labels="above">
				<field label="Titel">
					<text-input key="title"/>
				</field>
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
				<field label="Titel">
					<text-input key="title"/>
				</field>
				<field label="Adresse">
					<text-input key="url"/>
				</field>
				<field label="Interval (sekunder)">
					<number-input key="syncInterval"/>
				</field>
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
				<space left="5" right="5">
				<formula name="newsFormula">
					<group labels="above">
						<field label="Titel">
							<text-input key="title"/>
						</field>
						<field label="Note">
							<text-input key="note" multiline="true"/>
						</field>
					</group>
					<columns space="10">
						<column>
							<field label="Fra">
								<datetime-input key="startdate" return-type="seconds"/>
							</field>
						</column>
						<column>
							<field label="Til">
								<datetime-input key="enddate" return-type="seconds"/>
							</field>
						</column>
					</columns>
					<group labels="above">
						<field label="Grupper:">
							<checkboxes name="newsGroups">
								<items source="groupSource"/>
							</checkboxes>
						</field>
					</group>
				</formula>
				</space>
			</tab>
			
			<tab title="Links">
				<toolbar centered="true">
					<icon title="Tilføj link" icon="common/link" overlay="new" click="newsLinks.addLink()"/>
				</toolbar>
				<space right="10" left="10" bottom="10">
				<links name="newsLinks" pageSource="pageItems" fileSource="fileItems"/>
				</space>
			</tab>
			
		</tabs>
		
		<buttons right="15" bottom="10" align="right">
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
				<field label="Titel">
					<text-input key="title"/>
				</field>
				<field label="Opsummering">
					<text-input key="summary" multiline="true"/>
				</field>
				<field label="Tekst">
					<text-input key="text" multiline="true"/>
				</field>
				<field label="Link">
					<text-input key="linkText"/>
				</field>
				<field label="Skabelon">
					<dropdown key="blueprint" name="articleBlueprint">'.$blueprintItems.'</dropdown>
				</field>
			</group>
			<columns>
				<column>
					<field label="Fra">
						<datetime-input key="startdate"/>
					</field>
				</column>
				<column>
					<field label="Til">
						<datetime-input key="enddate"/>
					</field>
				</column>
			</columns>
			<group labels="above">
				<field label="Grupper:">
					<checkboxes key="groups">
						<items source="groupSource"/>
					</checkboxes>
				</field>
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