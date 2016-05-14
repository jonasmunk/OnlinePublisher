<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Issues">
	<controller source="controller.js"/>
	<controller source="status.js"/>
	
	<source name="listSource" url="data/ListIssues.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="type" value="@selector.value"/>
		<parameter key="kind" value="@kindSelector.value"/>
		<parameter key="status" value="@statusSelector.value"/>
		<parameter key="text" value="@search.value"/>
	</source>
	
	<source name="statusListSource" url="data/ListStates.php"/>
	
	<source name="sidebarSource" url="data/Sidebar.php"/>
	<source name="kindSelectorSource" url="data/SidebarKinds.php"/>
	<source name="statusSelectorSource" url="data/SidebarStates.php"/>
	<source name="statusSource" url="data/StatusItems.php"/>
	
	<structure>
		<top>
			<toolbar>
				<icon icon="common/note" overlay="new" title="Ny sag" name="addIssue"/>
				<divider/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/delete" title="{Delete; da:Slet}" name="delete" disabled="true">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete issue; da:Ja, slet sagen}" cancel="{No; da:Nej}"/>
				</icon>
				<field label="{Change type; da:Skift type}">
					<dropdown name="changeKind" placeholder="{Change type...;da:Skift type...}">
					'.GuiUtils::buildTranslatedItems(IssueService::getKinds()).'
					</dropdown>
				</field>
				<right>
					<field label="{Search; da:Søgning}">
						<searchfield expanded-width="200" name="search"/>
					</field>
					<divider/>
					<icon icon="common/settings" title="{Settings; da:Indstillinger}" name="settings" click="pages.next()"/>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="all" name="selector" top="5">
					<items source="sidebarSource"/>
				</selection>
				<selection value="any" name="statusSelector" top="5">
					<items source="statusSelectorSource"/>
				</selection>
				<selection value="any" name="kindSelector" top="5">
					<items source="kindSelectorSource"/>
				</selection>
				</overflow>
			</left>
			<center>
				<pages height="full" name="pages">
					<page key="list">
						<overflow>
							<list name="list" source="listSource" variant="light"/>
						</overflow>
					</page>
					<page key="settings" background="sand_grey">
						<box title="{Settings; da:Indstillinger}" width="400" top="20">
							<toolbar>
								<icon icon="common/object" overlay="new" title="{New status; da:Ny status}" name="newStatus"/>
							</toolbar>
							<!--
							<formula name="settingsFormula" padding="10">
								<fields labels="above">
									<field label="{E-mail; da:E-post}">
										<text-input key="email"/>
									</field>
									<buttons>
										<button name="saveSettings" title="{Save; da:Gem}" highlighted="true" submit="true"/>
									</buttons>
								</fields>
							</formula>
							-->
							<list name="statusList" source="statusListSource" variant="light"/>
						</box>
					</page>
				</pages>
			</center>
		</middle>
		<bottom/>
	</structure>

	<window title="{Issue; da:Sag}" name="issueWindow" icon="common/folder" width="300" padding="10">
		<formula name="issueFormula">
			<fields labels="above">
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
				<field label="{Text; da:Tekst}">
					<text-input key="note" multiline="true"/>
				</field>
				<field label="Type">
					<dropdown key="kind">
						'.GuiUtils::buildTranslatedItems(IssueService::getKinds()).'
					</dropdown>
				</field>
				<field label="Status">
					<dropdown key="statusId" source="statusSource"/>
				</field>
				<buttons>
					<button name="cancelIssue" title="{Cancel; da:Annuller}"/>
					<button name="deleteIssue" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete issue; da:Ja, slet sagen}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveIssue" title="{Save; da:Gem}" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>

	<window title="{Status; da:Status}" name="statusWindow" icon="common/folder" width="300" padding="10">
		<formula name="statusFormula">
			<fields labels="above">
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
				<buttons>
					<button name="cancelStatus" title="{Cancel; da:Annuller}"/>
					<button name="deleteStatus" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveStatus" title="{Save; da:Gem}" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
</gui>';

UI::render($gui);
?>