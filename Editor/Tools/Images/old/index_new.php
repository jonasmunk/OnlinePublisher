<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/FileSystemUtil.php';
require_once '../../Classes/GuiUtils.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemUtil::getMaxUploadSize());

$gui='
<gui xmlns="uri:In2iGui" title="Documents" padding="10">
	<controller source="controller.js"/>
	<source name="groupSource" url="GroupItems.php"/>
	<!--<source name="groupSource" url="../../Services/Model/Items.php?type=filegroup"/>-->
	<source name="filesSource" url="ListFiles.php?type=file">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="group" value="@groupSelection.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="file/generic" title="Tilføj fil" overlay="upload" name="newFile"/>
				<icon icon="common/folder" title="Ny gruppe" name="newGroup" overlay="new"/>
				<divider/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/delete" title="Slet" name="delete" disabled="true"/>
				<icon icon="file/generic" title="Hent fil" overlay="download" name="download" disabled="true"/>
				<icon icon="common/view" title="Vis fil" name="view" disabled="true"/>
				<right>
					<searchfield title="Søgning" name="search" expandedWidth="200"/>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<selection value="all" name="selector">
					<item icon="common/files" title="Alle" value="all"/>
					<title>Grupper</title>
					<items source="groupSource" name="groupSelection"/>
				</selection>
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
				<upload name="file" url="UploadFile.php" widget="upload">
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
	<window title="Gruppe" name="groupWindow" width="300" padding="5">
		<formula name="groupFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<buttons>
					<button name="cancelGroup" title="Annuller"/>
					<button name="deleteGroup" title="Slet"/>
					<button name="saveGroup" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	<window title="Fil" name="fileWindow" width="300" padding="5">
		<formula name="fileFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<checkboxes label="Grupper:" name="fileGroups" key="groups">
					<items source="groupSource"/>
				</checkboxes>
				<buttons>
					<button name="cancelFile" title="Annuller"/>
					<button name="deleteFile" title="Slet"/>
					<button name="updateFile" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>