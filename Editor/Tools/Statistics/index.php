<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Statistics">
	<controller source="controller.js"/>
	<source name="listSource" url="data/List.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="kind" value="@selector.value"/>
		<parameter key="time" value="@time.value"/>
	</source>
	<structure>
		<top>
			<toolbar>
				<field label="Startdato">
					<datetime-input name="startDate"/>
				</field>
				<field label="Slutdato">
					<datetime-input name="endDate"/>
				</field>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection name="selector" value="pages">
					<item icon="common/page" title="{Pages; da:Sider}" value="pages"/>
					<item icon="common/internet" title="{Paths; da:Stier}" value="pagePath"/>
					<item icon="common/time" title="{Live; da:Live}" value="live"/>
					<title>Teknik</title>
					<item icon="common/settings" title="{Applications; da:Programmer}" value="browsers"/>
					<item icon="common/settings" title="{Application versions; da:Programversioner}" value="browserVersions"/>
				</selection>
				<selection name="time" value="always">
					<title>{Time; da:Tid}</title>
					<item icon="common/time" title="{Always; da:Altid}" value="always"/>
					<item icon="common/time" title="{Latest year; da:Seneste år}" value="year"/>
					<item icon="common/time" title="{Latest month; da:Seneste måned}" value="month"/>
					<item icon="common/time" title="{Latest week; da:Seneste uge}" value="week"/>
				</selection>
				</overflow>
			</left>
			<center>
				<bar variant="layout">
					<segmented>
						<item text="Yearly" value="yearly"/>
						<item text="Monthly" value="monthly"/>
						<item text="Weekly" value="weekly"/>
						<item text="Daily" value="daily"/>
						<item text="Hourly" value="hourly"/>
					</segmented>
				</bar>
				<overflow>
					<list name="list" source="listSource">
						<error text="{Unable to retrieve statistics, please check that the login is correct; da:Det lykkedes ikke at hente statistikken, kontrolér venligst at login\'et er korrekt}"/>
					</list>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
</gui>';

In2iGui::render($gui);
?>