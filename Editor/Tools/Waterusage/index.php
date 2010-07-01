<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterssage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/FileSystemUtil.php';
require_once '../../Classes/GuiUtils.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemUtil::getMaxUploadSize());

$gui='
<gui xmlns="uri:In2iGui" title="Vandforbrug" padding="10">
	<controller source="controller.js"/>
	<source name="listSource" url="List.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="year" value="@selector.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="file/generic" title="Upload aflæsninger" overlay="upload" name="updateData" click="uploadWindow.show();"/>
				<icon icon="file/generic" title="Ny aflæsning" overlay="new" name="newUsage"/>
				<right>
					<searchfield title="Søgning" name="search" expandedWidth="200"/>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<selection value="0" name="selector">
					<item icon="common/files" title="Alle" value="0"/>
					<title>År</title>
					<item icon="common/time" title="2007" value="2007"/>
					<item icon="common/time" title="2008" value="2008"/>
					<item icon="common/time" title="2009" value="2009"/>
					<item icon="common/time" title="2010" value="2010"/>
				</selection>
			</left>
			<center>
				<overflow>
					<list name="list" source="listSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</layout>
	<window title="Import af data" name="uploadWindow" width="300" padding="5">
		<upload name="file" url="Upload.php" widget="upload">
			<placeholder title="Upload CSV-fil med målerdata" text="Filen skal have formatet år;nummer;værdi f.eks. (2009;6778888;67545) og kan højest være '.$maxUploadSize.' stor"/>
		</upload>
		<buttons align="center" top="10">
			<button name="upload" title="Vælg filer..." highlighted="true"/>
		</buttons>
	</window>
	<window title="Vandforbrug" name="usageWindow" width="300" padding="5">
		<formula name="usageFormula">
			<group labels="above">
				<text label="Nummer" key="number"/>
				<number label="År" key="year"/>
				<number label="Value" key="value" max="1000000000"/>
				<datetime label="Tidspunkt" key="date" return-type="seconds"/>
				<buttons>
					<button name="cancelUsage" title="Annuller"/>
					<button name="deleteUsage" title="Slet"/>
					<button name="saveUsage" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>