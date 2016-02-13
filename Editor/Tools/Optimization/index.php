<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="{Optimization;da:Optimering}" state="overview">
	<controller source="controller.js"/>
	<controller source="settings.js"/>
	<source name="listSource" url="data/List.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="kind" value="@selector.value"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
	</source>
	
	<source name="profilesSource" url="data/ListProfiles.php"/>
	
	<structure>
		<top>
			<toolbar>
				<icon icon="common/new" title="{Add control word; da:Tilføj kontrolord}" name="newWord"/>
				<icon icon="common/info" title="{Analyze; da:Analysér}" name="analyse" disabled="true"/>
				<icon icon="common/refresh" title="{Re-index; da:Indekser}" name="reindex" disabled="true"/>
				<!--icon icon="common/edit" title="Rediger"/>
				-->
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="overview" name="selector" top="5">
					<item icon="common/info" title="{Overview; da:Oversigt}" value="overview"/>
					<title>{Warnings; da:Advarsler}</title>
					<item icon="common/warning" title="{Requests; da:Forespørgsler}" value="pagenotfound"/>
					<item icon="common/warning" title="{Warnings; da:Advarsler}" value="warnings"/>
					<title>Sprog</title>
					<item icon="common/search" title="{Search index; da:Søgeindeks}" value="index"/>
					<item icon="common/info" title="{Unique words; da:Unikke ord}" value="words"/>
					<item icon="common/info" title="{Control words; da:Kontroller ord}" value="wordcheck"/>
				</selection>
				</overflow>
			</left>
			<center>
				<bar state="list" variant="layout">
					<text name="listDescription"/>
				</bar>
				<overflow>
					<list name="list" source="listSource" state="list"/>
					<fragment state="overview" height="full" background="vichy">
					<space all="20">
						<box width="100%" padding="10" title="{Overview; da:Oversigt}">
							<toolbar>
								<icon icon="common/save" text="{Save; da:Gem}" name="saveSettings"/>
							</toolbar>
							<formula name="settingsFormula">
								<fields labels="above">
									<field label="{What is the purpose of the website?; da:Hvad er hjemmesidens formål}:">
										<text-input multiline="true" key="purpose"/>
									</field>
									<field label="{What are the target audiences?; da:Hvilke målgrupper har siden}:">
										<text-input multiline="true" key="audiences"/>
									</field>
									<field label="{What are the success criteria for the website?; da:Hvad er success-kriterierne for siden?}:">
										<text-input multiline="true" key="successcriteria"/>
									</field>
								</fields>
							</formula>
							<list variant="light" name="profilesList" selectable="false" source="profilesSource">
								
							</list>
							<buttons top="10">
								<button variant="light" small="true" text="{Add profile;da:Tilføj profil}" name="newSite" click="profilePanel.show(); profileFormula.focus()"/>
							</buttons>
						</box>
						</space>
					</fragment>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<boundpanel target="newSite" name="profilePanel" width="200" variant="light" modal="true">
		<formula name="profileFormula" padding="5">
			<fields labels="above">
				<field label="{Title; da:Titel}:">
					<text-input key="name"/>
				</field>
				<field label="{Address; da:Adresse}:">
					<text-input key="url"/>
				</field>
			</fields>
			<buttons>
				<button text="{Close; da:Luk}" click="profileFormula.reset();profilePanel.hide()" small="true"/>
				<button text="{Add; da:Tilføj}" highlighted="true" submit="true" small="true"/>
			</buttons>
		</formula>
	
	</boundpanel>
	
	<boundpanel target="newWord" name="newWordPanel" width="200">
		<formula name="wordFormula">
			<fields labels="above">
				<field label="{Word; da:Ord}:">
					<text-input key="word"/>
				</field>
			</fields>
			<buttons>
				<button text="{Close; da:Luk}" click="newWordPanel.hide()" small="true"/>
				<button text="{Add; da:Tilføj}" highlighted="true" submit="true" small="true"/>
			</buttons>
		</formula>
	</boundpanel>
	
	<boundpanel name="wordPanel" width="300" title="Sider med ordet" modal="true" variant="light">
		<overflow height="200">
			<list name="phrasePageList" variant="light" selectable="false"/>
		</overflow>
	</boundpanel>
	
	<window name="analysisWindow" title="Analysis" width="400">
		<div id="analysis" style="height: 400px; overflow: auto;"></div>
	</window>
	
</gui>';

UI::render($gui);
?>