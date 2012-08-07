<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="{Calendars; da:Kalendere}" padding="10" state="list">

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
				<icon icon="common/calendar" title="{New calendar; da:Ny kalender}" overlay="new" name="newCalendar"/>
				<icon icon="common/internet" title="{New source; da:Ny kilde}" overlay="new" name="newSource"/>
				<icon icon="common/time" title="{New event; da:Ny begivenhed}" overlay="new" name="newEvent"/>
				<divider/>
				<icon icon="common/refresh" title="{Synchronize; da:Synkroniser}" name="synchronizeSource" disabled="true"/>
				<icon icon="common/delete" title="{Delete; da:Slet}" disabled="true" name="deleteItem">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel; da:Annuller}"/>
				</icon>
				<icon icon="common/edit" title="{Edit; da:Rediger}" disabled="true" name="editItem"/>
				<divider/>
				<field label="{View; da:Visning}">
					<segmented value="list" name="viewSelection">
						<item icon="view/list" value="list"/>
						<item icon="view/calendar" value="calendar"/>
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
				<selection value="overview" name="selector">
					<item icon="common/files" title="{Overview; da:Oversigt}" value="overview"/>
					<items source="calendarItemsSource" name="calendarSelection" title="{Calendars; da:Kalendere}"/>
					<items source="sourcesItemsSource" name="sourceSelection" title="{Sources; da:Kilder}"/>
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
	
	<window title="{Source; da:Kilde}" name="sourceWindow" width="300" padding="5">
		<formula name="sourceFormula">
			<fields labels="above">
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
				<field label="{Title (display); da:Titel (visning)}">
					<text-input key="displayTitle"/>
				</field>
				<field label="{Address; da:Adresse}">
					<text-input key="url" multiline="true"/>
				</field>
				<field label="Filter">
					<text-input key="filter"/>
				</field>
				<field label="Interval ({seconds; da:sekunder})">
					<number-input key="syncInterval"/>
				</field>
			</fields>
			<buttons>
				<button name="cancelSource" title="{Cancel; da:Annuller}"/>
				<button name="deleteSource" title="{Delete; da:Slet}">
					<confirm text="(Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel; da:Annuller}"/>
				</button>
				<button name="saveSource" title="{Save; da:Gem}" highlighted="true"/>
			</buttons>
		</formula>
	</window>
	
	<window title="{Calendar; da:Kalender}" name="calendarWindow" width="300" padding="5">
		<formula name="calendarFormula">
			<fields labels="above">
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
			</fields>
			<buttons>
				<button name="cancelCalendar" title="{Cancel; da:Annuller}"/>
				<button name="deleteCalendar" title="{Delete; da:Slet}">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel; da:Annuller}"/>
				</button>
				<button name="saveCalendar" title="{Save; da:Gem}" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</window>
	
	<window title="{Event; da:Begivenhed}" icon="common/time" name="eventWindow" width="300" padding="5">
		<formula name="eventFormula">
			<fields labels="above">
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
				<field label="{Location; da:Lokation}">
					<text-input key="location"/>
				</field>
				<field label="{From; da:Fra}">
					<datetime-input key="startdate"/>
				</field>
				<field label="{To; da:Til}">
					<datetime-input key="enddate"/>
				</field>
				<field label="{Calendars; da:Kalendere}">
					<checkboxes key="calendars" name="eventCalendars">
						<items source="calendarItemsSource"/>
					</checkboxes>
				</field>
			</fields>
			<buttons>
				<button name="cancelEvent" title="{Cancel; da:Annuller}"/>
				<button name="deleteEvent" title="{Delete; da:Slet}">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel; da:Annuller}"/>
				</button>
				<button name="saveEvent" title="{Save; da:Gem}" highlighted="true" submit="true"/>
			</buttons>
		</formula>
	</window>
	
</gui>';

In2iGui::render($gui);
?>