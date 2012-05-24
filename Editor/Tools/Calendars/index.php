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
				<field label="Visning">
					<segmented value="list" name="viewSelection">
						<item icon="view/list" value="list"/>
						<item icon="view/calendar" value="calendar"/>
					</segmented>
				</field>
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
				<field label="Interval (sekunder)">
					<number-input key="syncInterval"/>
				</field>
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
				<field label="Titel">
					<text key="title"/>
				</field>
			</group>
			<buttons>
				<button name="cancelCalendar" title="Annuller"/>
				<button name="deleteCalendar" title="Slet">
					<confirm text="Er du sikker" ok="Ja, slet" cancel="Annuller"/>
				</button>
				<button name="saveCalendar" title="Gem" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</window>
	
	<window title="Begivenhed" icon="common/time" name="eventWindow" width="300" padding="5">
		<formula name="eventFormula">
			<group labels="above">
				<field label="Titel">
					<text-input key="title"/>
				</field>
				<field label="Lokation">
					<text-input key="location"/>
				</field>
				<field label="Fra">
					<datetime-input key="startdate"/>
				</field>
				<field label="Til">
					<datetime-input key="enddate"/>
				</field>
				<field label="Kalendere">
					<checkboxes key="calendars" name="eventCalendars">
						<items source="calendarItemsSource"/>
					</checkboxes>
				</field>
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