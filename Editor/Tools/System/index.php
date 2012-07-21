<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System" state="list">
	<controller source="controller.js"/>
	<controller source="designController.js"/>
	<controller source="settingsController.js"/>
	<source name="allObjectsSource" url="../../Services/Model/ListObjects.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@searchField.value"/>
	</source>
	<source name="inspectionSource" url="data/InspectionList.php">
		<parameter key="status" value="@inspectionStatus.value"/>
		<parameter key="category" value="@inspectionCategory.value"/>
	</source>
	<source name="logSource" url="data/ListLog.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@searchField.value"/>
		<parameter key="showIpSession" value="@logIpSession.value"/>
		<parameter key="category" value="@logCategory.value"/>
		<parameter key="event" value="@logEvent.value"/>
	</source>
	<source name="cachesSource" url="data/CacheList.php"/>
	<source name="logCategoriesSource" url="data/LogCategoryItems.php"/>
	<source name="logEventsSource" url="data/LogEventItems.php"/>
	<structure>
		<top>
			<toolbar>
				<icon icon="common/user" title="Ny bruger" name="newUser" overlay="new"/>
				<icon icon="common/folder" title="Ny weblog gruppe" name="newWeblogGroup" overlay="new"/>
				<icon icon="common/internet" title="Ny sti" name="newPath" overlay="new"/>
				<icon icon="common/color" title="Nyt design" name="newDesign" overlay="new"/>
				<right>
					<field label="Søgning">
						<searchfield name="searchField"/>
					</field>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="object" name="selector">
						<item icon="common/object" title="Alle objekter" value="object"/>
						<item icon="common/user" title="Brugere" value="user"/>
						<item icon="file/generic" title="Log" value="log"/>
						<item icon="common/folder" title="Weblog grupper" value="webloggroup"/>
						<item icon="common/internet" title="Stier" value="path"/>
						<item icon="common/color" title="Designs" value="design"/>
						<item icon="common/page" title="Skabeloner" value="templates"/>
						<item icon="common/tools" title="Værktøjer" value="tools"/>
						<title>Database</title>
						<item icon="common/folder" title="Status" value="databaseInfo"/>
						<item icon="common/folder" title="Tabeller" value="databaseTables"/>
						<title>Indstillinger</title>
						<item icon="common/settings" title="Indstillinger" value="settings"/>
						<item icon="common/time" title="Cache" value="caches"/>
						<item icon="common/warning" title="Problemer" value="inspection"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<bar state="inspection" variant="layout">
						<segmented value="all" name="inspectionCategory">
							<item text="Alle" value="all"/>
							<item text="Omgivelser" value="environment"/>
							<item text="System" value="system"/>
							<item text="Indhold" value="content"/>
							<item text="Model" value="model"/>
						</segmented>
						<space/>
						<segmented value="all" name="inspectionStatus">
							<item text="Alle" value="all"/>
							<item text="Advarsler" value="warning"/>
							<item text="OK" value="ok"/>
							<item text="Fejl" value="error"/>
						</segmented>
						<right>
							<button text="Refresh" click="inspectionList.refresh()" small="true"/>
						</right>
					</bar>
					<bar name="logBar" variant="layout" visible="false">
						<dropdown value="all" name="logCategory" source="logCategoriesSource"/>
						<dropdown value="all" name="logEvent" source="logEventsSource"/>
						<checkbox name="logIpSession" label="Show IP + session"/>
					</bar>
					<list name="list" source="allObjectsSource" state="list"/>
					<list name="inspectionList" source="inspectionSource" state="inspection"/>
					<fragment state="caches" height="full" background="linen">
						<box width="500" top="30" title="Caches">
							<toolbar>
								<icon icon="common/refresh" click="cachesList.refresh()" text="Genopfrisk"/>
							</toolbar>
							<list name="cachesList" source="cachesSource" selectable="false"/>
						</box>
					</fragment>
					<fragment state="settings" height="full" background="vichy">
						<box width="500" top="30" variant="rounded">
							<tabs small="true" centered="true">
								<tab title="Brugergrænseflade">
									<space all="10" bottom="5">
										<formula name="uiFormula">
											<fields>
												<field label="Moderne rig tekst editor:">
													<checkbox key="experimentalRichText"/>
												</field>
												<field label="Shared secret">
													<text-input key="sharedSecret"/>
												</field>
											</fields>
											<buttons>
												<button title="Opdater" name="saveUI" highlighted="true"/>
											</buttons>
										</formula>
									</space>
								</tab>
								<tab title="E-mail">
									<space all="10" bottom="5">
										<formula name="emailFormula">
											<fields>
												<field label="Aktiv:">
													<checkbox key="enabled"/>
												</field>
												<field label="Server:">
													<text-input key="server"/>
												</field>
												<field label="Port:">
													<text-input key="port"/>
												</field>
												<field label="Brugernavn:">
													<text-input key="username"/>
												</field>
												<field label="Kodeord:">
													<text-input key="password" secret="true"/>
												</field>
												<field label="Navn:">
													<text-input key="standardName"/>
												</field>
												<field label="E-mail:">
													<text-input key="standardEmail"/>
												</field>
											</fields>
											<fieldset legend="Feedback">
											<fields>
												<field label="Feedback-navn:">
													<text-input key="feedbackName"/>
												</field>
												<field label="Feedback E-mail:">
													<text-input key="feedbackEmail"/>
												</field>
											</fields>
											</fieldset>
											<fields>
												<buttons>
													<button title="Test" name="showEmailTest"/>
													<button title="Opdater" name="saveEmail" highlighted="true"/>
												</buttons>
											</fields>
										</formula>
									</space>
								</tab>
								<tab title="Google Analytics">
									<space all="10" bottom="5">
										<formula name="analyticsFormula">
											<fields>
												<field label="Brugernavn:">
													<text-input key="username"/>
												</field>
												<field label="Kodeord:">
													<text-input secret="true" key="password"/>
												</field>
												<field label="Profil ID:">
													<text-input key="profile"/>
												</field>
												<field label="Web profil ID:">
													<text-input key="webProfile"/>
												</field>
												<buttons>
													<button name="testAnalytics" title="Test"/>
													<button name="saveAnalytics" title="Opdater" highlighted="true"/>
												</buttons>
											</fields>
										</formula>
									</space>
								</tab>
								<tab title="OnlineObjects">
									<space all="10" bottom="5">
										<formula name="onlineobjectsFormula">
											<fields>
												<field label="Adresse:">
													<text-input key="url"/>
												</field>
												<buttons>
													<button name="testOnlineObjects" title="Test"/>
													<button name="saveOnlineObjects" title="Opdater" highlighted="true"/>
												</buttons>
											</fields>
										</formula>
									</space>
								</tab>
							</tabs>
						</box>
					</fragment>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<window name="userEditor" width="300" title="Bruger" padding="5">
		<formula name="userFormula">
			<overflow height="200">
			<fields>
				<field label="Titel:">
					<text-input name="userTitle"/>
				</field>
				<field label="Brugernavn:">
					<text-input name="userUsername"/>
				</field>
				<field label="Kodeord:">
					<text-input name="userPassword" secret="true"/>
				</field>
				<field label="E-mail:">
					<text-input name="userEmail"/>
				</field>
				<field label="Notat:">
					<text-input name="userNote" lines="6"/>
				</field>
				<field label="Intern adgang:">
					<checkbox name="userInternal"/>
				</field>
				<field label="Ekstern adgang:">
					<checkbox name="userExternal"/>
				</field>
				<field label="Administrator:">
					<checkbox name="userAdministrator"/>
				</field>
			</fields>
			</overflow>
			<fields>
				<buttons>
					<button name="cancelUser" title="Annuller"/>
					<button name="deleteUser" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet brugeren" cancel="Nej, jeg fortryder"/>
					</button>
					<button name="saveUser" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="pathEditor" width="300" title="Sti" padding="5">
		<formula name="pathFormula">
			<fields>
				<field label="Sti:">
					<text-input key="path"/>
				</field>
				<field label="Side:">
					<dropdown key="pageId" placeholder="Vælg side..." url="../../Services/Model/Items.php?type=page"/>
				</field>
				<buttons>
					<button name="cancelPath" title="Annuller"/>
					<button name="deletePath" title="Slet"/>
					<button name="savePath" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="weblogGroupEditor" width="300" title="Weblog gruppe" padding="5">
		<formula name="weblogGroupFormula">
			<fields>
				<field label="Titel:">
					<text-input key="title"/>
				</field>
				<field label="Notat:">
					<text-input key="note" lines="10"/>
				</field>
				<buttons>
					<button name="cancelWeblogGroup" title="Annuller"/>
					<button name="deleteWeblogGroup" title="Slet"/>
					<button name="saveWeblogGroup" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="designEditor" width="300" title="Design" padding="5">
		<formula name="designFormula">
			<fields>
				<field label="Titel:">
					<text-input key="title"/>
				</field>
				<field label="Design:">
					<dropdown key="unique" placeholder="Vælg design..." url="data/DesignItems.php"/>
				</field>
				<buttons>
					<button name="cancelDesign" title="Annuller"/>
					<button name="deleteDesign" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet design" cancel="Nej"/>
					</button>
					<button name="saveDesign" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="emailTestWindow" width="300" title="Test af E-mail" padding="5">
		<formula name="emailTestFormula">
			<fields labels="above">
				<field label="Navn:">
					<text-input key="name"/>
				</field>
				<field label="E-mail:">
					<text-input key="email"/>
				</field>
				<field label="Emne:">
					<text-input key="subject"/>
				</field>
				<field label="Besked:">
					<text-input key="body" multiline="true"/>
				</field>
				<buttons>
					<button name="testEmail" title="Test" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
</gui>';

In2iGui::render($gui);
?>