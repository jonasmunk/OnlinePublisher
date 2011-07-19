<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';

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
	<source name="warningsSource" url="data/WarningsList.php">
	
	</source>
	<source name="logSource" url="ListLog.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@searchField.value"/>
	</source>
	<source name="cachesSource" url="data/CacheList.php"/>
	<layout>
		<top>
			<toolbar>
				<icon icon="common/user" title="Ny bruger" name="newUser" overlay="new"/>
				<icon icon="common/folder" title="Ny weblog gruppe" name="newWeblogGroup" overlay="new"/>
				<icon icon="common/internet" title="Ny sti" name="newPath" overlay="new"/>
				<icon icon="common/color" title="Nyt design" name="newDesign" overlay="new"/>
				<right>
					<searchfield title="Søgning" name="searchField"/>
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
						<title>Database</title>
						<item icon="common/folder" title="Status" value="databaseInfo"/>
						<item icon="common/folder" title="Tabeller" value="databaseTables"/>
						<title>Indstillinger</title>
						<item icon="common/settings" title="Indstillinger" value="settings"/>
						<item icon="common/time" title="Cache" value="caches"/>
						<item icon="common/warning" title="Problemer" value="warnings"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="allObjectsSource" state="list"/>
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
											<group>
												<checkbox key="experimentalRichText" label="Moderne rig tekst editor:"/>
											</group>
										</formula>
									</space>
								</tab>
								<tab title="E-mail">
									<space all="10" bottom="5">
										<formula name="emailFormula">
											<group>
												<checkbox key="enabled" label="Aktiv:"/>
												<text key="server" label="Server:"/>
												<text key="port" label="Port:"/>
												<text key="username" label="Brugernavn:"/>
												<text key="password" secret="true" label="Kodeord:"/>
												<text key="standardName" label="Navn:"/>
												<text key="standardEmail" label="E-mail:"/>
											</group>
											<fieldset legend="Feedback">
											<group>
												<text key="feedbackName" label="Feedback-navn:"/>
												<text key="feedbackEmail" label="Feedback E-mail:"/>
											</group>
											</fieldset>
											<group>
												<buttons>
													<button title="Test" name="showEmailTest"/>
													<button title="Opdater" name="saveEmail" highlighted="true"/>
												</buttons>
											</group>
										</formula>
									</space>
								</tab>
								<tab title="Google Analytics">
									<space all="10" bottom="5">
										<formula name="analyticsFormula">
											<group>
												<text key="username" label="Brugernavn:"/>
												<text secret="true" key="password" label="Kodeord:"/>
												<text key="profile" label="Profil ID:"/>
												<text key="webProfile" label="Web profil ID:"/>
												<buttons>
													<button name="testAnalytics" title="Test"/>
													<button name="saveAnalytics" title="Opdater" highlighted="true"/>
												</buttons>
											</group>
										</formula>
									</space>
								</tab>
								<tab title="OnlineObjects">
									<space all="10" bottom="5">
										<formula name="onlineobjectsFormula">
											<group>
												<text key="url" label="Adresse:"/>
												<buttons>
													<button name="testOnlineObjects" title="Test"/>
													<button name="saveOnlineObjects" title="Opdater" highlighted="true"/>
												</buttons>
											</group>
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
	</layout>
	<window name="userEditor" width="300" title="Bruger" padding="5">
		<formula name="userFormula">
			<overflow height="200">
			<group>
				<text name="userTitle" label="Titel:"/>
				<text name="userUsername" label="Brugernavn:"/>
				<text name="userPassword" secret="true" label="Kodeord:"/>
				<text name="userEmail" label="E-mail:"/>
				<text name="userNote" label="Notat:" lines="6"/>
				<checkbox name="userInternal" label="Intern adgang:"/>
				<checkbox name="userExternal" label="Ekstern adgang:"/>
				<checkbox name="userAdministrator" label="Administrator:"/>
			</group>
			</overflow>
			<group>
				<buttons>
					<button name="cancelUser" title="Annuller"/>
					<button name="deleteUser" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet brugeren" cancel="Nej, jeg fortryder"/>
					</button>
					<button name="saveUser" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	<window name="pathEditor" width="300" title="Sti" padding="5">
		<formula name="pathFormula">
			<group>
				<text key="path" label="Sti:"/>
				<dropdown key="pageId" label="Side:" placeholder="Vælg side..." url="../../Services/Model/Items.php?type=page"/>
				<buttons>
					<button name="cancelPath" title="Annuller"/>
					<button name="deletePath" title="Slet"/>
					<button name="savePath" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	<window name="weblogGroupEditor" width="300" title="Weblog gruppe" padding="5">
		<formula name="weblogGroupFormula">
			<group>
				<text key="title" label="Titel:"/>
				<text key="note" label="Notat:" lines="10"/>
				<buttons>
					<button name="cancelWeblogGroup" title="Annuller"/>
					<button name="deleteWeblogGroup" title="Slet"/>
					<button name="saveWeblogGroup" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window name="designEditor" width="300" title="Design" padding="5">
		<formula name="designFormula">
			<group>
				<text key="title" label="Titel:"/>
				<dropdown key="unique" label="Design:" placeholder="Vælg design..." url="DesignItems.php"/>
				<buttons>
					<button name="cancelDesign" title="Annuller"/>
					<button name="deleteDesign" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet design" cancel="Nej"/>
					</button>
					<button name="saveDesign" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window name="emailTestWindow" width="300" title="Test af E-mail" padding="5">
		<formula name="emailTestFormula">
			<group labels="above">
				<text key="name" label="Navn:"/>
				<text key="email" label="E-mail:"/>
				<text key="subject" label="Emne:"/>
				<text key="body" label="Besked:" lines="5"/>
				<buttons>
					<button name="testEmail" title="Test" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
</gui>';
//$gui='<gui xmlns="uri:hui" pad="10" title="System"><upload/></gui>';

In2iGui::render($gui);
?>