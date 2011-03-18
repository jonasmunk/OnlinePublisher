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
	<layout>
		<top>
			<toolbar>
				<icon icon="file/generic" title="Importér" overlay="upload" name="updateData" click="uploadWindow.show();"/>
				<icon icon="file/generic" title="Eksportér" overlay="download" name="export"/>
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
					<title>Aflæsninger</title>
					<item icon="common/water" title="Alle aflæsninger" value="usage"/>
					<item icon="common/time" title="2007" value="2007"/>
					<item icon="common/time" title="2008" value="2008"/>
					<item icon="common/time" title="2009" value="2009"/>
					<item icon="common/time" title="2010" value="2010"/>
				</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="listSource" state="list"/>
					<fragment state="meter" height="full" background="leather">
						<bar>
							<button text="Luk" icon="common/close" click="ui.changeState(\'list\')"/>
						</bar>
						<block all="20">
							<columns space="20">
								<column width="400px">
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
											<button text="Opdater" submit="true"/>
										</buttons>
										</formula>
									</box>
								</column>
								<column>
									<box variant="rounded">
										<bar>
											<button text="Tilføj aflæsning"/>
										</bar>
										<overflow height="200">
											<list name="usageList">
												<column title="Værdi" key="value"/>
												<column title="Dato" key="date"/>
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
	
	<window title="Vandforbrug" name="meterUsageFormula" width="300" padding="5">
		<formula name="meterUsageFormula">
			<group labels="above">
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
	
	<window title="Import af data" name="uploadWindow" width="300" padding="5">
		<upload name="file" url="Upload.php" widget="upload" flash="false">
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

	<window title="Vandmåler" name="meterWindow" width="300" padding="5">
		<formula name="meterFormula">
			<group labels="above">
				<text label="Nummer" key="number"/>
				<buttons>
					<button name="cancelMeter" title="Annuller"/>
					<button name="deleteMeter" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
					</button>
					<button name="saveMeter" submit="true" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>