<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/FileSystemUtil.php';
require_once '../../Classes/GuiUtils.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemUtil::getMaxUploadSize());

$gui='
<gui xmlns="uri:In2iGui" title="Nyheder" padding="10">
	<controller source="controller.js"/>
	<source name="pageItems" url="../../Services/Model/Items.php?type=page"/>
	<source name="fileItems" url="../../Services/Model/Items.php?type=file"/>
	<source name="groupSource" url="GroupItems.php"/>
	<source name="newsSource" url="ListNews.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="group" value="@groupSelection.value"/>
		<parameter key="main" value="@selector.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="common/folder" title="Ny gruppe" name="newGroup" overlay="new"/>
				<icon icon="common/news" title="Ny nyhed" name="newNews" overlay="new"/>
				<divider/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/delete" title="Slet" name="delete" disabled="true">
					<confirm text="Er du sikker?" ok="Ja, slet nyheden" cancel="Annuller"/>
				</icon>
				<icon icon="common/info" title="Kopier" name="duplicate" disabled="true"/>
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
					<item icon="common/internet" title="Eksterne" value="url"/>
					<item icon="common/page" title="Sider" value="page"/>
					<item icon="common/page" title="Filer" value="file"/>
					<item icon="common/email" title="E-mail" value="email"/>
					<title>Grupper</title>
					<items source="groupSource" name="groupSelection"/>
				</selection>
				</overflow>
			</left>
			<center>
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
	
	
	<window title="Nyhed" name="newsWindow" icon="file/generic" width="450">
	
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
	
	
	<box title="Ny nyhed" name="newNewsBox" absolute="true" padding="10" modal="true" width="636" variant="textured" closable="true">
		<formula name="newNewsFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<text label="Note" key="note" multiline="true"/>
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
				<button name="cancelNewNews" title="Annuller" click="newNewsBox.hide()"/>
				<button name="createNewNews" title="Opret" highlighted="true"/>
			</buttons>
		</formula>
	</box>
</gui>';

In2iGui::render($gui);
?>