<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/FileSystemUtil.php';
require_once '../../../Classes/GuiUtils.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemUtil::getMaxUploadSize());
$flash = $_SERVER['SERVER_NAME']==='localhost' ? 'false' : 'true';
$gui='
<gui xmlns="uri:In2iGui" title="Filer" padding="10">
	<controller source="controller.js"/>
	<controller source="groups.js"/>
	<controller source="upload.js"/>
	<source name="groupSource" url="GroupItems.php"/>
	<!--<source name="typesSource" url="TypeItems.php"/>-->
	<source name="imagesSource" url="GallerySource.php">
		<parameter key="text" value="@search.value"/>
		<parameter key="group" value="@groupSelection.value"/>
	</source>
	<source name="filesSource" url="ListImages.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="group" value="@groupSelection.value"/>
		<parameter key="type" value="@typeSelection.value"/>
		<parameter key="main" value="@selector.value"/>
	</source>
	<layout>
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
				<icon icon="file/generic" title="Erstat" name="replace" overlay="change" disabled="true"/>
				<icon icon="common/view" title="Vis" name="view" disabled="true"/>
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
					<title>Grupper</title>
					<items source="groupSource" name="groupSelection"/>
					<title>Typer</title>
					<!--<items source="typesSource" name="typeSelection"/>-->
				</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<gallery name="gallery" source="imagesSource"/>
					<!--
					<list name="list" source="filesSource"/>
					-->
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
	
	<window title="Tilføjelse af nyt billede" name="uploadWindow" width="300">
		<tabs small="true" centered="true">
			<tab title="Upload" padding="10">
				<upload name="file" url="UploadImage.php" widget="upload" flash="'.$flash.'">
					<placeholder title="Vælg billeder på din computer..." text="Filen kan højest være '.$maxUploadSize.' stor"/>
				</upload>
				<buttons align="center" top="10">
					<button name="cancelUpload" title="Annuller"/>
					<button name="upload" title="Vælg billder..." highlighted="true"/>
				</buttons>
			</tab>
			<tab title="Hent fra nettet" padding="10">
				<formula name="fetchFormula">
					<group labels="above">
					<text label="Adresse:" key="url"/>
					<buttons>
						<button name="fetchImage" title="Hent" highlighted="true"/>
					</buttons>
					</group>
				</formula>
			</tab>
		</tabs>
	</window>
	
	<window title="Billede" name="imageWindow" icon="file/generic" width="300" padding="5">
		<formula name="imageFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<checkboxes label="Grupper:" name="imageGroups" key="groups">
					<items source="groupSource"/>
				</checkboxes>
				<buttons>
					<button name="cancelImage" title="Annuller"/>
					<button name="deleteImage" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet billedet" cancel="Annuller"/>
					</button>
					<button name="saveImage" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>