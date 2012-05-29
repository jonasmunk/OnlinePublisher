<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Services/FileSystemService.php';
require_once '../../Classes/Utilities/GuiUtils.php';

//$uploadAddToGroup = InternalSession::getToolSessionVar('images','uploadAddToGroup',true) ? 'true' : 'false';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());

$gui='
<gui xmlns="uri:hui" title="Billeder" padding="10" state="gallery">
	<controller source="controller.js"/>
	<controller source="groups.js"/>
	<controller source="upload.js"/>
	<source name="subsetSource" url="data/Selection.php"/>
	<source name="groupOptionsSource" url="../../Services/Model/Items.php?type=imagegroup"/>
	<source name="groupSource" url="GroupItems.php"/>
	<!--<source name="typesSource" url="TypeItems.php"/>-->
	<source name="imagesSource" url="GallerySource.php">
		<parameter key="text" value="@search.value"/>
		<parameter key="group" value="@groupSelection.value"/>
		<parameter key="subset" value="@subsetSelection.value"/>
	</source>
	<source name="listSource" url="data/ListImages.php">
		<parameter key="text" value="@search.value"/>
		<parameter key="subset" value="@subsetSelection.value"/>
		<parameter key="group" value="@groupSelection.value"/>
		<parameter key="windowPage" value="@list.window.page"/>
	</source>
	<structure>
		<top>
			<toolbar>
				<icon icon="common/image" title="{Add file ; da:Tilføj billede}" overlay="upload" name="newFile"/>
				<icon icon="common/folder" title="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
				<divider/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/delete" title="Slet" name="delete" disabled="true">
					<confirm text="Er du sikker?" ok="Ja, slet billedet" cancel="Annuller"/>
				</icon>
				<icon icon="file/generic" title="Hent" overlay="download" name="download" disabled="true"/>
				<icon icon="common/view" title="Vis" name="view" disabled="true"/>
				<divider/>
				<field label="Visning">
					<segmented value="gallery" name="viewSwitch">
						<item value="list" icon="view/list"/>
						<item value="gallery" icon="view/gallery"/>
					</segmented>
				</field>
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
						<items source="subsetSource" name="subsetSelection"/>
						<items source="groupSource" name="groupSelection" title="Grupper"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow name="mainArea">
					<gallery name="gallery" source="imagesSource" padding="5" state="gallery" drop-files="true"/>
					<list name="list" source="listSource" state="list" drop-files="true"/>
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
	
	<window title="Tilføjelse af nyt billede" name="uploadWindow" width="300">
		<tabs small="true" centered="true">
			<tab title="Upload" padding="10">
				<upload name="file" url="UploadImage.php" widget="upload" multiple="true">
					<placeholder title="Vælg billeder på din computer..." text="Filen kan højest være '.$maxUploadSize.' stor"/>
				</upload>
				<buttons align="center" top="10">
					<button name="cancelUpload" title="Luk"/>
					<button name="upload" title="Vælg billeder..." highlighted="true"/>
				</buttons>
			</tab>
			<tab title="Hent fra nettet" padding="10">
				<formula name="fetchFormula">
					<group labels="above">
						<field label="Adresse:">
							<text-input key="url"/>
						</field>
					</group>
				</formula>
				<buttons align="center">
					<button name="cancelFetch" title="Annuller"/>
					<button name="fetchImage" title="Hent" highlighted="true"/>
				</buttons>
			</tab>
		</tabs>
	</window>
	
	<window title="Billede" name="imageWindow" icon="file/generic" width="300" padding="5">
		<formula name="imageFormula">
			<group labels="above">
				<field label="Titel">
					<text-input key="title"/>
				</field>
				<field label="Grupper:">
					<checkboxes name="imageGroups" key="groups" max-height="200">
						<items source="groupOptionsSource"/>
					</checkboxes>
				</field>
				<buttons>
					<button name="cancelImage" title="Annuller"/>
					<button name="deleteImage" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet billedet" cancel="Annuller"/>
					</button>
					<button name="saveImage" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>