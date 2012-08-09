<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../Include/Private.php';

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
				<icon icon="common/user" title="{New user; da:Ny bruger}" name="newUser" overlay="new"/>
				<icon icon="common/folder" title="{New weblog group; da:Ny weblog gruppe}" name="newWeblogGroup" overlay="new"/>
				<icon icon="common/internet" title="{New path; da:Ny sti}" name="newPath" overlay="new"/>
				<icon icon="common/color" title="{New design; da:Nyt design}" name="newDesign" overlay="new"/>
				<right>
					<field label="{Search; da:Søgning}">
						<searchfield name="searchField"/>
					</field>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="object" name="selector">
						<item icon="common/object" title="{All objects; da:Alle objekter}" value="object"/>
						<item icon="common/user" title="{Users; da:Brugere}" value="user"/>
						<item icon="file/generic" title="Log" value="log"/>
						<item icon="common/folder" title="{Weblog groups; da:Weblog grupper}" value="webloggroup"/>
						<item icon="common/internet" title="{Paths; da:Stier}" value="path"/>
						<item icon="common/color" title="Designs" value="design"/>
						<item icon="common/page" title="{Templates; da:Skabeloner}" value="templates"/>
						<item icon="common/tools" title="{Tools; da:Værktøjer}" value="tools"/>
						<title>Database</title>
						<item icon="common/folder" title="Status" value="databaseInfo"/>
						<item icon="common/folder" title="{Tables; da:Tabeller}" value="databaseTables"/>
						<title>Indstillinger</title>
						<item icon="common/settings" title="{Settings; da:Indstillinger}" value="settings"/>
						<item icon="common/time" title="Cache" value="caches"/>
						<item icon="common/warning" title="{Problems; da:Problemer}" value="inspection"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<bar state="inspection" variant="layout">
						<segmented value="all" name="inspectionCategory">
							<item text="{All; da:Alle}" value="all"/>
							<item text="{Environment; da:Omgivelser}" value="environment"/>
							<item text="System" value="system"/>
							<item text="{Content; da:Indhold}" value="content"/>
							<item text="Model" value="model"/>
						</segmented>
						<space/>
						<segmented value="all" name="inspectionStatus">
							<item text="{All; da:Alle}" value="all"/>
							<item text="{Warnings; da:Advarsler}" value="warning"/>
							<item text="OK" value="ok"/>
							<item text="{Errors; da:Fejl}" value="error"/>
						</segmented>
						<right>
							<button text="{Genindlæs; da:Refresh}" click="inspectionList.refresh()" small="true"/>
						</right>
					</bar>
					<bar name="logBar" variant="layout" visible="false">
						<dropdown value="all" name="logCategory" source="logCategoriesSource"/>
						<dropdown value="all" name="logEvent" source="logEventsSource"/>
						<checkbox name="logIpSession" label="{Show IP + session; da:Vis IP + session}"/>
					</bar>
					<list name="list" source="allObjectsSource" state="list"/>
					<list name="inspectionList" source="inspectionSource" state="inspection"/>
					<fragment state="caches" height="full" background="linen">
						<box width="500" top="30" title="Caches">
							<toolbar>
								<icon icon="common/refresh" click="cachesList.refresh()" text="{Refresh; da:Genopfrisk}"/>
							</toolbar>
							<list name="cachesList" source="cachesSource" selectable="false"/>
						</box>
					</fragment>
					<fragment state="settings" height="full" background="vichy">
						<box width="500" top="30" variant="rounded">
							<tabs small="true" centered="true">
								<tab title="{User interface; da:Brugergrænseflade}">
									<space all="10" bottom="5">
										<formula name="uiFormula">
											<fields>
												<field label="{Modern rich text editor; da:Moderne rig tekst editor}:">
													<checkbox key="experimentalRichText"/>
												</field>
												<field label="{Delt nøgle; da:Shared secret}">
													<text-input key="sharedSecret"/>
												</field>
											</fields>
											<buttons>
												<button title="{Update; da:Opdater}" name="saveUI" highlighted="true"/>
											</buttons>
										</formula>
									</space>
								</tab>
								<tab title="{E-post; da:E-mail}">
									<space all="10" bottom="5">
										<formula name="emailFormula">
											<fields>
												<field label="{Active; da:Aktiv}:">
													<checkbox key="enabled"/>
												</field>
												<field label="Server:">
													<text-input key="server"/>
												</field>
												<field label="Port:">
													<text-input key="port"/>
												</field>
												<field label="{Username; da:Brugernavn}:">
													<text-input key="username"/>
												</field>
												<field label="{Password; da:Kodeord}:">
													<text-input key="password" secret="true"/>
												</field>
												<field label="{Name; da:Navn}:">
													<text-input key="standardName"/>
												</field>
												<field label="{E-mail; da:E-post}:">
													<text-input key="standardEmail"/>
												</field>
											</fields>
											<fieldset legend="{Feedback; da:Tilbagemelding}">
											<fields>
												<field label="{Name; da:Navn}:">
													<text-input key="feedbackName"/>
												</field>
												<field label="{E-mail; da:E-post}:">
													<text-input key="feedbackEmail"/>
												</field>
											</fields>
											</fieldset>
											<fields>
												<buttons>
													<button title="{Test; da:Afprøv}" name="showEmailTest"/>
													<button title="{Update; da:Opdater}" name="saveEmail" highlighted="true"/>
												</buttons>
											</fields>
										</formula>
									</space>
								</tab>
								<tab title="Google Analytics">
									<space all="10" bottom="5">
										<formula name="analyticsFormula">
											<fields>
												<field label="{Username; da:Brugernavn}:">
													<text-input key="username"/>
												</field>
												<field label="{Password; da:Kodeord}:">
													<text-input secret="true" key="password"/>
												</field>
												<field label="{Profile ID; da:Profil-ID}:">
													<text-input key="profile"/>
												</field>
												<field label="{Web profile ID; da:Web-profil-ID}:">
													<text-input key="webProfile"/>
												</field>
												<buttons>
													<button name="testAnalytics" title="{Test; da:Afprøv}"/>
													<button name="saveAnalytics" title="{Update; da:Opdater}" highlighted="true"/>
												</buttons>
											</fields>
										</formula>
									</space>
								</tab>
								<tab title="OnlineObjects">
									<space all="10" bottom="5">
										<formula name="onlineobjectsFormula">
											<fields>
												<field label="{Address; da:Adresse}:">
													<text-input key="url"/>
												</field>
												<buttons>
													<button name="testOnlineObjects" title="{Afprøv; da:Test}"/>
													<button name="saveOnlineObjects" title="{Update; da:Opdater}" highlighted="true"/>
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
	
	<window name="userEditor" width="300" title="{User; da:Bruger}" padding="5">
		<formula name="userFormula">
			<overflow height="200">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input name="userTitle"/>
				</field>
				<field label="{Username; da:Brugernavn}:">
					<text-input name="userUsername"/>
				</field>
				<field label="{Password; da:Kodeord}:">
					<text-input name="userPassword" secret="true"/>
				</field>
				<field label="{E-post; da:E-mail}:">
					<text-input name="userEmail"/>
				</field>
				<field label="{Note; da:Notat}:">
					<text-input name="userNote" lines="6"/>
				</field>
				<field label="{Internal access; da:Intern adgang}:">
					<checkbox name="userInternal"/>
				</field>
				<field label="{External access; da:Ekstern adgang}:">
					<checkbox name="userExternal"/>
				</field>
				<field label="Administrator:">
					<checkbox name="userAdministrator"/>
				</field>
			</fields>
			</overflow>
			<fields>
				<buttons>
					<button name="cancelUser" title="{Cancel; da:Annuller}"/>
					<button name="deleteUser" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete user; da:Ja, slet brugeren}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveUser" title="{Save; da:Gem}" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="pathEditor" width="300" title="{Path; da:Sti}" padding="5">
		<formula name="pathFormula">
			<fields>
				<field label="{Path; da:Sti}:">
					<text-input key="path"/>
				</field>
				<field label="{Page; da:Side}:">
					<dropdown key="pageId" placeholder="{Select page...; da:Vælg side...}" url="../../Services/Model/Items.php?type=page"/>
				</field>
				<buttons>
					<button name="cancelPath" title="{Cancel; da:Annuller}"/>
					<button name="deletePath" title="{Delete; da:Slet}"/>
					<button name="savePath" title="{Save; da:Gem}" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="weblogGroupEditor" width="300" title="{Weblog group; da:Weblog gruppe}" padding="5">
		<formula name="weblogGroupFormula">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input key="title"/>
				</field>
				<field label="{Note; da:Notat}:">
					<text-input key="note" lines="10"/>
				</field>
				<buttons>
					<button name="cancelWeblogGroup" title="{Cancel; da:Annuller}"/>
					<button name="deleteWeblogGroup" title="{Delete; da:Slet}"/>
					<button name="saveWeblogGroup" title="{Save; da:Gem}" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="designEditor" width="300" title="Design" padding="5">
		<formula name="designFormula">
			<fields>
				<field label="{Title; da:Titel}:">
					<text-input key="title"/>
				</field>
				<field label="Design:">
					<dropdown key="unique" placeholder="{Select design...; da:Vælg design...}" url="data/DesignItems.php"/>
				</field>
				<buttons>
					<button name="cancelDesign" title="{Cancel; da:Annuller}"/>
					<button name="deleteDesign" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete design; da:Ja, slet design}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveDesign" title="{Save; da:Gem}" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="emailTestWindow" width="300" title="{Test of e-mail; da:Test af e-post}" padding="5">
		<formula name="emailTestFormula">
			<fields labels="above">
				<field label="{Name; da:Navn}:">
					<text-input key="name"/>
				</field>
				<field label="{E-post; da:E-mail}:">
					<text-input key="email"/>
				</field>
				<field label="{Subject; da:Emne}:">
					<text-input key="subject"/>
				</field>
				<field label="{Message; da:Besked}:">
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