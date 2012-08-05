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
<gui xmlns="uri:hui" title="{News; da:Nyheder}" padding="10">
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
				<icon icon="common/news" title="{New news item; da:Ny nyhed}" name="newNews" overlay="new"/>
				'.($blueprintItems ? '<icon icon="common/page" title="{New article; da:Ny artikel}" name="newArticle" overlay="new"/>' : '').'
				<icon icon="common/folder" title="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
				<icon icon="common/internet" title="{New source; da:Ny kilde}" name="newSource" overlay="new"/>
				<divider/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/delete" title="{Delete; da:Slet}" name="delete" disabled="true">
					<confirm text="{Are yu sure?; da:Er du sikker?}" ok="{Yes, delete news; da:Ja, slet nyheden}" cancel="{Cancel; da:Annuller}"/>
				</icon>
				<icon icon="common/duplicate" title="{Duplicate; da:Dubler}" name="duplicate" disabled="true"/>
				<right>
					<field label="{Search; da:Søgning}">
						<searchfield name="search" expanded-width="200"/>
					</field>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="all" name="selector">
					<item icon="common/files" title="{All; da:Alle}" value="all"/>
					<item icon="common/time" title="{Latest 24 hours; da:Seneste døgn}" value="latest"/>
					<item icon="common/play" title="{Active; da:Aktive}" value="active"/>
					<item icon="common/stop" title="{Inactive; da:Inaktive}" value="inactive"/>
					<title>Links</title>
					<item icon="monochrome/globe" title="{External; da:Eksterne}" value="url"/>
					<item icon="monochrome/file" title="{Page; da:Sider}" value="page"/>
					<item icon="monochrome/file" title="{Files; da:Filer}" value="file"/>
					<item icon="monochrome/email" title="{E-mail; da:E-post}" value="email"/>
					<items source="groupSource" name="groupSelection" title="{Groups; da:Grupper}"/>
					<items source="sourcesSource" name="sourceSelection" title="{Sources; da:Kilder}"/>
				</selection>
				</overflow>
			</left>
			<center>
				<bar variant="layout" state="source">
					<text name="sourceHeader" variant="header"/>
					<text name="sourceText"/>
					<right>
						<button text="Info" name="sourceInfo" small="true" rounded="true"/>
						<button text="{Synchronize; da:Synkroniser}" name="synchronize" small="true" rounded="true"/>
					</right>
				</bar>
				<bar variant="layout" state="group">
					<text name="groupHeader" variant="header"/>
					<right>
						<button text="Info" name="groupInfo" small="true" rounded="true"/>
						<button text="{RSS-address; da:RSS-adresse}" name="groupRSS" small="true" rounded="true"/>
					</right>
				</bar>
				<overflow>
					<list name="list" source="newsSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<window title="{Group; da:Gruppe}" name="groupWindow" icon="common/folder" width="300" padding="5">
		<space left="5" right="5" bottom="3">
			<formula name="groupFormula">
				<fields labels="above">
					<field label="{Title; da:Titel}">
						<text-input key="title"/>
					</field>
					<buttons>
						<button name="cancelGroup" title="{Cancel; da:Annuller}"/>
						<button name="deleteGroup" title="{Delete; da:Slet}">
							<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete group; da:Ja, slet gruppen}" cancel="{No; da:Nej}"/>
						</button>
						<button name="saveGroup" title="{Save; da:Gem}" highlighted="true"/>
					</buttons>
				</fields>
			</formula>
		</space>
	</window>

	<window title="{Source; da:Kilde}" name="sourceWindow" icon="common/internet" width="300" padding="5">
		<formula name="sourceFormula">
			<fields labels="above">
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
				<field label="{Address; da:Adresse}">
					<text-input key="url"/>
				</field>
				<field label="Interval ({seconds; da:sekunder})" hint="1 time = 3600 sekunder">
					<number-input key="syncInterval"/>
				</field>
				<buttons>
					<button name="cancelSource" title="{Cancel; da:Annuller}"/>
					<button name="deleteSource" title="{Delete; da:Slet}">
						<confirm text="{Are you sure? da:Er du sikker?}" ok="{Yes, delete source; da:Ja, slet kilden}" cancel="{No; da:Nej}"/>
					</button>
					<button submit="true" title="{Save; da:Gem}" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	
	<window title="{News; da:Nyhed}" name="newsWindow" variant="news" icon="common/news" width="450">
	
		<tabs small="true" centered="true">
		
			<tab title="{News; da:Nyhed}" padding="10">
				<space left="5" right="5">
				<formula name="newsFormula">
					<fields labels="above">
						<field label="{Title; da:Titel}">
							<text-input key="title"/>
						</field>
						<field label="Note">
							<text-input key="note" multiline="true"/>
						</field>
					</fields>
					<columns space="10">
						<column>
							<field label="{From; da:Fra}">
								<datetime-input key="startdate" return-type="seconds"/>
							</field>
						</column>
						<column>
							<field label="{To; da:Til}">
								<datetime-input key="enddate" return-type="seconds"/>
							</field>
						</column>
					</columns>
					<fields labels="above">
						<field label="{Groups; da:Grupper}:">
							<checkboxes name="newsGroups">
								<items source="groupSource"/>
							</checkboxes>
						</field>
					</fields>
				</formula>
				</space>
			</tab>
			
			<tab title="Links">
				<toolbar centered="true">
					<icon title="{New link; da:Nyt link}" icon="common/link" overlay="new" click="newsLinks.addLink()"/>
				</toolbar>
				<space right="10" left="10" bottom="10">
				<links name="newsLinks" pageSource="pageItems" fileSource="fileItems"/>
				</space>
			</tab>
			
		</tabs>
		
		<buttons right="16" bottom="10" align="right">
			<button name="cancelNews" title="{Cancel; da:Annuller}"/>
			<button name="deleteNews" title="{Delete; da:Slet}">
				<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete news; da:Ja, slet nyheden}" cancel="{Cancel; da:Annuller}"/>
			</button>
			<button name="updateNews" title="{Save; da:Gem}" highlighted="true"/>
		</buttons>
		
	</window>
	
	
	<box title="{New article; da:Ny artikel}" name="newArticleBox" absolute="true" padding="10" modal="true" width="636" variant="textured" closable="true">
		<formula name="articleFormula">
			<fields labels="above">
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
				<field label="{Summary; da:Opsummering}">
					<text-input key="summary" multiline="true"/>
				</field>
				<field label="{Text; da:Tekst}">
					<text-input key="text" multiline="true"/>
				</field>
				<field label="Link">
					<text-input key="linkText"/>
				</field>
				<field label="{Template; da:Skabelon}">
					<dropdown key="blueprint" name="articleBlueprint">'.$blueprintItems.'</dropdown>
				</field>
			</fields>
			<columns>
				<column>
					<field label="{From; da:Fra}">
						<datetime-input key="startdate"/>
					</field>
				</column>
				<column>
					<field label="{To; da:Til}">
						<datetime-input key="enddate"/>
					</field>
				</column>
			</columns>
			<fields labels="above">
				<field label="{Groups; da:Grupper}:">
					<checkboxes key="groups">
						<items source="groupSource"/>
					</checkboxes>
				</field>
			</fields>
			<buttons>
				<button name="cancelNewArticle" title="{Cancle; da:Annuller}" click="newNewsBox.hide()"/>
				<button name="createNewArticle" title="{Create; da:Opret}" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</box>
</gui>';

In2iGui::render($gui);
?>