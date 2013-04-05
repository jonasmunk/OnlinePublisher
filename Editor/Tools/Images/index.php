<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../Include/Private.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());

$gui='
<gui xmlns="uri:hui" title="Billeder" padding="10" state="gallery">
	<controller source="controller.js"/>
	<controller source="groups.js"/>
	<controller source="upload.js"/>
	<source name="subsetSource" url="data/Selection.php"/>
	<source name="groupOptionsSource" url="../../Services/Model/Items.php?type=imagegroup"/>
	<source name="groupSource" url="data/GroupItems.php"/>
	<!--<source name="typesSource" url="TypeItems.php"/>-->
	<source name="imagesSource" url="data/GallerySource.php">
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
				<icon icon="common/image" title="{Add image ; da:Tilføj billede}" overlay="upload" name="newFile"/>
				<icon icon="common/folder" title="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
				<divider/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/delete" title="{Delete; da:Slet}" name="delete" disabled="true">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete image; da:Ja, slet billedet}" cancel="{No; da:Nej}"/>
				</icon>
				<icon icon="file/generic" title="{Download; da:Hent}" overlay="download" name="download" disabled="true"/>
				<icon icon="common/view" title="{View; da:Vis}" name="view" disabled="true"/>
				<divider/>
				<field label="{View; da:Visning}">
					<segmented value="gallery" name="viewSwitch">
						<item value="list" icon="view/list"/>
						<item value="gallery" icon="view/gallery"/>
					</segmented>
				</field>
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
					<selection value="all" name="selector" top="5">
						<items source="subsetSource" name="subsetSelection"/>
						<items source="groupSource" name="groupSelection" title="{Groups; da:Grupper}"/>
					</selection>
				</overflow>
			</left>
			<center>
				<bar name="groupBar" variant="layout" visible="false">
					<text name="groupTitle"/>
					<right>
						<button text="Info" small="true" name="groupInfo"/>
					</right>
				</bar>
				<overflow name="mainArea">
					<gallery name="gallery" source="imagesSource" padding="5" state="gallery" drop-files="true"/>
					<list name="list" source="listSource" state="list" drop-files="true"/>
				</overflow>
			</center>
		</middle>
		<bottom>
			<div style="float: right; margin: 1px 8px 0 0;">
				<slider width="200" name="sizeSlider" value="0.5"/>
			</div>
		</bottom>
	</structure>
	
	<window title="{Group; da:Gruppe}" name="groupWindow" icon="common/folder" width="300" padding="5">
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
	</window>
	
	<window title="{Addition of images; da:Tilføjelse af billeder}" name="uploadWindow" width="300">
		<tabs small="true" centered="true">
			<tab title="{Upload; da:Overførsel}" padding="10">
				<upload name="file" url="actions/UploadImage.php" widget="upload" multiple="true">
					<placeholder title="{Select images on your computer...; da:Vælg billeder på din computer...}" text="{The file can at most be '.$maxUploadSize.' large; da:Filen kan højest være '.$maxUploadSize.' stor}"/>
				</upload>
				<buttons align="center" top="10">
					<button name="cancelUpload" title="{Close; da:Luk}"/>
					<button name="upload" title="{Select images...; da:Vælg billeder...}" highlighted="true"/>
				</buttons>
			</tab>
			<tab title="{Fetch from the net; da:Hent fra nettet}" padding="10">
				<formula name="fetchFormula">
					<fields labels="above">
						<field label="{Address; da:Adresse}:">
							<text-input key="url"/>
						</field>
					</fields>
				</formula>
				<buttons align="center">
					<button name="cancelFetch" title="{Cancel; da:Annuller}"/>
					<button name="fetchImage" title="{Fetch; da:Hent}" highlighted="true"/>
				</buttons>
			</tab>
		</tabs>
	</window>
	
	<window title="{Image; da:Billede}" name="imageWindow" icon="common/image" width="450" padding="5">
		<formula name="imageFormula">
			<columns flexible="true">
				<column width="180px">
					<div style="width: 150px; min-height: 50px; max-height: 300px; overflow: hidden; background: #fff no-repeat; font-size: 0; padding: 3px; border: 1px solid #ccc; border-color: #ddd #ccc #bbb; margin: 5px;" id="photo"></div>
				</column>
				<column>
					<fields labels="above">
						<field label="{Title; da:Titel}">
							<text-input key="title"/>
						</field>
						<field label="{Groups; da:Grupper}:">
							<checkboxes name="imageGroups" key="groups" max-height="200">
								<items source="groupOptionsSource"/>
							</checkboxes>
						</field>
					</fields>
				</column>
			</columns>
			<buttons>
				<button name="cancelImage" title="{Cancel; da:Annuller}"/>
				<button name="deleteImage" title="{Delete; da:Slet}">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete image; da:Ja, slet billedet}" cancel="{Cancel; da:Annuller}"/>
				</button>
				<button name="saveImage" title="{Save; da:Gem}" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>