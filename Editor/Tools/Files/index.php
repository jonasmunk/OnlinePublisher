<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Services/FileSystemService.php';
require_once '../../Classes/Utilities/GuiUtils.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());
$flash = $_SERVER['SERVER_NAME']==='localhost' ? 'false' : 'true';
//$flash = 'true';
$gui='
<gui xmlns="uri:In2iGui" title="Filer" padding="10">
	<controller source="controller.js"/>
	<controller source="replace.js"/>
	<source name="groupSource" url="GroupItems.php"/>
	<source name="typesSource" url="TypeItems.php"/>
	<source name="filesSource" url="ListFiles.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="group" value="@groupSelection.value"/>
		<parameter key="type" value="@typeSelection.value"/>
		<parameter key="main" value="@selector.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="file/generic" title="{Add file ; da:Tilføj fil}" overlay="upload" name="newFile"/>
				<icon icon="common/folder" title="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
				<divider/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/delete" title="Slet" name="delete" disabled="true">
					<confirm text="Er du sikker?" ok="Ja, slet filen" cancel="Annuller"/>
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
					<items source="typesSource" name="typeSelection"/>
				</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="filesSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</layout>
	<window title="Tilføjelse af ny fil" name="uploadWindow" width="300">
		<tabs small="true" centered="true">
			<tab title="Upload" padding="10">
				<upload name="file" url="UploadFile.php" widget="upload" flash="'.$flash.'">
					<placeholder title="Vælg filer på din computer..." text="Filen kan højest være '.$maxUploadSize.' stor"/>
				</upload>
				<buttons align="center" top="10">
					<button name="cancelUpload" title="Annuller"/>
					<button name="upload" title="Vælg filer..." highlighted="true"/>
				</buttons>
			</tab>
			<tab title="Hent fra nettet" padding="10">
				<formula name="fetchFormula">
					<group labels="above">
					<text label="Adresse:" key="url"/>
					<buttons>
						<button name="fetchFile" title="Hent" highlighted="true"/>
					</buttons>
					</group>
				</formula>
			</tab>
		</tabs>
	</window>
	
	<window title="Erstatning af fil" name="replaceWindow" width="300" padding="10">
		<upload name="replaceFile" url="ReplaceFile.php" widget="replaceUpload" flash="false">
			<placeholder title="Vælg en ny fil på din computer..." text="Filen kan højest være '.$maxUploadSize.' stor"/>
		</upload>
		<buttons align="center" top="10">
			<button name="cancelReplaceUpload" title="Annuller"/>
			<button name="replaceUpload" title="Vælg fil..." highlighted="true"/>
		</buttons>
	</window>
	
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
	<window title="Fil" name="fileWindow" icon="file/generic" width="300" padding="5">
		<formula name="fileFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<checkboxes label="Grupper:" name="fileGroups" key="groups">
					<items source="groupSource"/>
				</checkboxes>
				<buttons>
					<button name="cancelFile" title="Annuller"/>
					<button name="deleteFile" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet filen" cancel="Annuller"/>
					</button>
					<button name="updateFile" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>