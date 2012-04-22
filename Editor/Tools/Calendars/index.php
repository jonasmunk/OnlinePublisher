<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Services/FileSystemService.php';
require_once '../../Classes/Utilities/GuiUtils.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());

$gui='
<gui xmlns="uri:hui" title="Documents" padding="10" state="list">

	<controller source="controller.js"/>
	<controller source="sourceController.js"/>
	<controller source="calendarController.js"/>
	<controller source="eventController.js"/>
	
	<source name="calendarItemsSource" url="../../Services/Model/Items.php?type=calendar"/>
	
	<source name="sourcesItemsSource" url="../../Services/Model/Items.php?type=calendarsource"/>
	
	<source name="sourcesListSource" url="data/ListSources.php"/>
	
	<source name="sourceEventsListSource" url="data/ListSourceEvents.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="sourceId" value="@sourceSelection.value"/>
	</source>
	
	<source name="calendarEventsListSource" url="data/ListCalendarEvents.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="calendarId" value="@calendarSelection.value"/>
	</source>
	
	<source name="calendarViewSource" url="data/CalendarViewEvents.php" lazy="true">
		<parameter key="query" value="@search.value"/>
		<parameter key="sourceId" value="@sourceSelection.value"/>
		<parameter key="startTime" value="@calendarView.startTime"/>
		<parameter key="endTime" value="@calendarView.endTime"/>
	</source>
	
	<structure>
		<top>
			<toolbar>
				<icon icon="common/calendar" title="Ny kalender" overlay="new" name="newCalendar"/>
				<icon icon="common/internet" title="Ny kilde" overlay="new" name="newSource"/>
				<icon icon="common/time" title="Ny begivenhed" overlay="new" name="newEvent"/>
				<divider/>
				<icon icon="common/refresh" title="Synkroniser" name="synchronizeSource" disabled="true"/>
				<icon icon="common/delete" title="Slet" disabled="true" name="deleteItem">
					<confirm text="Er du sikker?" ok="Ja, slet" cancel="Annuller"/>
				</icon>
				<icon icon="common/edit" title="Rediger" disabled="true" name="editItem"/>
				<divider/>
				<segmented label="Visning" value="list" name="viewSelection">
					<item icon="view/list" value="list"/>
					<item icon="view/calendar" value="calendar"/>
				</segmented>
				<right>
					<searchfield title="Søgning" name="search" expandedWidth="200"/>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="overview" name="selector">
					<item icon="common/files" title="Oversigt" value="overview"/>
					<title>Kallendere</title>
					<items source="calendarItemsSource" name="calendarSelection"/>
					<title>Kilder</title>
					<items source="sourcesItemsSource" name="sourceSelection"/>
				</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="sourcesListSource" state="list"/>
					<calendar state="calendar" source="calendarViewSource" name="calendarView"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<window title="Kilde" name="sourceWindow" width="300" padding="5">
		<formula name="sourceFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<text label="Titel (visning)" key="displayTitle"/>
				<text label="Adresse" key="url" multiline="true"/>
				<text label="Filter" key="filter"/>
				<number label="Interval (sekunder)" key="syncInterval"/>
				<buttons>
					<button name="cancelSource" title="Annuller"/>
					<button name="deleteSource" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Annuller"/>
					</button>
					<button name="saveSource" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window title="Kalender" name="calendarWindow" width="300" padding="5">
		<formula name="calendarFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<buttons>
					<button name="cancelCalendar" title="Annuller"/>
					<button name="deleteCalendar" title="Slet">
						<confirm text="Er du sikker" ok="Ja, slet" cancel="Annuller"/>
					</button>
					<button name="saveCalendar" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window title="Begivenhed" icon="common/time" name="eventWindow" width="300" padding="5">
		<formula name="eventFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<text label="Lokation" key="location"/>
				<datetime label="Fra" key="startdate"/>
				<datetime label="Til" key="enddate"/>
				<checkboxes label="Kalendere" key="calendars" name="eventCalendars">
					<items source="calendarItemsSource"/>
				</checkboxes>
				<buttons>
					<button name="cancelEvent" title="Annuller"/>
					<button name="deleteEvent" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Annuller"/>
					</button>
					<button name="saveEvent" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
</gui>';

In2iGui::render($gui);
?>