<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterssage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Services/FileSystemService.php';
require_once '../../Classes/Utilities/GuiUtils.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());

$gui='
<gui xmlns="uri:In2iGui" title="Vandforbrug" padding="10" state="list">
	<controller source="controller.js"/>
	<source name="listSource" url="List.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="filter" value="@selector.value"/>
	</source>
	<source name="filterSource" url="FilterItems.php"/>
	<layout>
		<top>
			<toolbar>
				<icon icon="file/generic" title="Importér" overlay="upload" name="import"/>
				<icon icon="file/generic" title="Eksportér" overlay="download" name="exportIcon"/>
				<divider/>
				<icon icon="common/gauge" title="Ny måler" overlay="new" name="newMeter"/>
				<icon icon="common/water" title="Ny aflæsning" overlay="new" name="newUsage"/>
				<right>
					<searchfield title="Søgning" name="search" expandedWidth="200"/>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="meters" name="selector">
					<item icon="common/gauge" title="Målere" value="meters"/>
					<item icon="file/generic" title="Log" value="log"/>
					<title>Aflæsninger</title>
					<item icon="common/water" title="Alle aflæsninger" value="usage"/>
					<items source="filterSource"/>
				</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="listSource" state="list"/>
					<fragment state="meter" height="full" background="leather">
						<bar>
							<button text="Luk" icon="common/close" name="closeMeter"/>
						</bar>
						<block all="20">
							<columns space="20">
								<column width="300px">
									<box variant="rounded" padding="10">
									<formula name="summaryFormula">
										<group labels="above">
											<text label="Nummer" key="number"/>
											<text label="Gade" key="street"/>
										</group>
										<columns>
											<column width="100px">
												<group labels="above">
													<text label="Postnummer" key="zipcode"/>
												</group>
											</column>
											<column>
												<group labels="above">
													<text label="By" key="city"/>
												</group>
											</column>
										</columns>
										<buttons>
											<button name="deleteMeter" title="Slet">
												<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
											</button>
											<button text="Opdater" submit="true" name="saveMeter"/>
										</buttons>
										</formula>
									</box>
								</column>
								<column>
									<box variant="rounded">
										<bar>
											<button text="Tilføj aflæsning" name="addSubUsage" icon="common/new"/>
										</bar>
										<overflow height="200">
											<list name="subUsageList">
											</list>
										</overflow>
									</box>
								</column>
							</columns>
						</block>
					</fragment>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</layout>
	
	<window title="Aflæsning" icon="common/water" name="subUsageWindow" width="300" padding="5">
		<formula name="subUsageFormula">
			<group labels="above">
				<number label="Værdi" key="value" max="1000000000"/>
				<datetime label="Tidspunkt" key="date" return-type="seconds"/>
				<buttons>
					<button name="cancelSubUsage" title="Annuller"/>
					<button name="deleteSubUsage" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
					</button>
					<button name="saveSubUsage" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window title="Import" name="importWindow" width="300">
		<tabs centered="true">
			<tab title="Vandmålere" padding="10">
				<upload name="metersUpload" url="ImportMeters.php" widget="meterImportButton" flash="false">
					<placeholder title="Upload af CSV-fil med vandmålere" text="Filen skal have formatet (nummer;vej;postnummer;by) f.eks. (6778888;Toldbodvej 1;9370;Hals) og kan højest være '.$maxUploadSize.' stor"/>
				</upload>
				<buttons align="center" top="10">
					<button name="meterImportButton" title="Vælg filer..." highlighted="true"/>
				</buttons>
			</tab>
			<tab title="Aflæsninger" padding="10">
				<upload name="usagesUpload" url="ImportUsages.php" widget="usagesImportButton" flash="false">
					<placeholder title="Upload af CSV-fil med aflæsninger" text="Filen skal have formatet (nummer;værdi;dato) f.eks. (6778888;21361;15/04/2011) og kan højest være '.$maxUploadSize.' stor"/>
				</upload>
				<buttons align="center" top="10">
					<button name="usagesImportButton" title="Vælg filer..." highlighted="true"/>
				</buttons>
			</tab>
		</tabs>
	</window>
	
	<window title="Aflæsning" icon="common/water" name="usageWindow" width="300" padding="5">
		<formula name="usageFormula">
			<group labels="above">
				<text label="Nummer" key="number"/>
				<!--<number label="År" key="year"/>-->
				<number label="Værdi" key="value" max="1000000000"/>
				<datetime label="Tidspunkt" key="date" return-type="seconds"/>
				<buttons>
					<button name="cancelUsage" title="Annuller"/>
					<button name="deleteUsage" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
					</button>
					<button name="saveUsage" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>

	<window title="Vandmåler" icon="common/gauge" name="meterWindow" width="300" padding="5">
		<formula name="meterFormula">
			<group labels="above">
				<text label="Nummer" key="number"/>
				<buttons>
					<button name="cancelMeter" title="Annuller"/>
					<button name="createMeter" submit="true" title="Opret" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<boundpanel name="exportPanel" target="exportIcon" width="200" padding="5">
		<text align="center">
			<h>Eksport</h>
			<p>Her kan du eksportere alle aflæsninger i CSV-formatet:</p><p>"Number","Date","Value","Updated"</p>
		</text>
		<buttons align="center">
			<button click="exportPanel.hide()" text="Annuller"/>
			<button name="export" title="Eksportér" highlighted="true"/>
		</buttons>
	</boundpanel>

</gui>';

In2iGui::render($gui);
?>