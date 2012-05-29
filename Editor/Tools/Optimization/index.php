<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System" state="overview">
	<controller source="controller.js"/>
	<source name="listSource" url="data/List.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="kind" value="@selector.value"/>
	</source>
	<structure>
		<top>
			<toolbar>
				<icon icon="common/new" title="Tilføj kontrolord" name="newWord" click="newWordPanel.show();wordFormula.focus()"/>
				<!--icon icon="common/edit" title="Rediger"/>
				-->
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="overview" name="selector">
					<item icon="common/info" title="Oversigt" value="overview"/>
					<item icon="common/warning" title="Advarsler" value="warnings"/>
					<title>Sprog</title>
					<item icon="common/search" title="Søgeindeks" value="index"/>
					<item icon="common/info" title="Unikke ord" value="words"/>
					<item icon="common/info" title="Kontroller ord" value="wordcheck"/>
				</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<bar state="list" variant="layout">
						<text name="listDescription"/>
					</bar>
					<list name="list" source="listSource" state="list"/>
					<fragment state="overview" height="full" background="vichy">
						<box width="500" padding="10" top="20" title="Oversigt">
							<toolbar>
								<icon icon="common/save" text="Gem" name="saveSettings"/>
							</toolbar>
							<formula name="settingsFormula">
								<fields labels="above">
									<field label="Hvad er hjemmesidens formål:">
										<text-input multiline="true" key="purpose"/>
									</field>
									<field label="Hvilke målgrupper har siden:">
										<text-input multiline="true" key="audiences"/>
									</field>
									<field label="Hvad er success-kriterierne for siden">
										<text-input multiline="true" key="successcriteria"/>
									</field>
								</fields>
							</formula>
						</box>
					</fragment>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<boundpanel target="newWord" name="newWordPanel" width="200">
		<formula name="wordFormula">
			<fields labels="above">
				<field label="Ord:">
					<text-input key="word"/>
				</field>
			</fields>
			<buttons>
				<button text="Luk" click="newWordPanel.hide()" small="true"/>
				<button text="Opret" highlighted="true" submit="true" small="true"/>
			</buttons>
		</formula>
	</boundpanel>
	
	<boundpanel name="wordPanel" width="300" title="Sider med ordet">
		<overflow height="200">
			<list name="phrasePageList"/>
		</overflow>
		<buttons top="10">
			<button small="true" text="Luk" click="wordPanel.hide()" highlighted="true"/>
		</buttons>
	</boundpanel>
	
</gui>';

In2iGui::render($gui);
?>