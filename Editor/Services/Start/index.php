<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/SystemInfo.php';

$user = User::load(InternalSession::getUserId());

$gui='
<gui xmlns="uri:hui" padding="10" title="Start">
	<css url="style.css"/>
	<controller name="controller" source="controller.js"/>
	<source name="taskSource" url="data/TaskList.php"/>
	<source name="developerFeed" url="data/DeveloperFeed.php"/>
	<source name="commitFeed" url="data/CommitFeed.php"/>
	<source name="newsFeed" url="data/NewsFeedArticles.php"/>
	<source name="warningsList" url="data/WarningsList.php"/>
	<div class="box">
		<div class="header">
			<span class="date"> version: '.SystemInfo::getFormattedDate().'</span>
			<span class="user">
				<icon icon="common/user" size="16"/>
				<strong>'.StringUtils::escapeXml($user->getTitle()).'</strong>
				<em>('.StringUtils::escapeXml($user->getUsername()).')</em>
				<button mini="true" variant="paper" text="{Settings ; da:Indstillinger}" name="userSettings"/>
			</span>
		</div>
		<div style="position: absolute; top: 76px; bottom: 5px; left: 5px; right: 5px; overflow: hidden;">
			<tiles space="10">
				<tile width="30" height="100" top="0" left="0" variant="light" name="taskTile">
					<actions>
						<!--icon icon="monochrome/info"/-->
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>{Tasks ; da: Opgaver }</title>
					<overflow full="true" background="sand_grey">
					<list source="taskSource" name="taskList" variant="transparent" selectable="false">
						<empty>
							<space all="10">
							<text>
								<p><strong>Der er ingen opgaver lige nu</strong></p>
								<p>Du kan oprette noter for sider under visningen af en side.</p>
								<p>Det gøres under fanebladet "Avanceret".</p>
							</text>
							</space>
						</empty>
					</list>
					</overflow>
				</tile>
				<tile width="30" height="100" top="0" left="30" variant="light" name="developmentTile">
					<actions>
						<icon icon="monochrome/round_arrow_left" key="previous"/>
						<icon icon="monochrome/round_arrow_right" key="next"/>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>{Development ; da: Udvikling }</title>
					<pages name="developmentPages" height="full">
						<page>
							<overflow full="true">
								<list source="newsFeed" name="newsList" selectable="false" indent="10"/>
							</overflow>
						</page>
						<page>
							<overflow full="true">
								<list source="developerFeed" selectable="false" indent="10"/>
							</overflow>
						</page>
						<page>
							<overflow full="true">
								<list source="commitFeed" selectable="false" indent="10"/>
							</overflow>
						</page>
					</pages>
				</tile>
				<tile width="40" height="50" top="0" left="60" variant="light">
					<actions>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>Feedback</title>
					<overflow full="true">
						<pages name="feedbackPages">
							<page>
								<formula padding="10" name="feedbackForm">
									<fields labels="above">
										<field label="{Write us with improvements and questions ; da: Skriv til os med ris, ros eller spørgsmål }">
											<text-input multiline="true" key="message"/>
										</field>
										<buttons>
											<button text="Send" submit="true" name="sendFeedback"/>
										</buttons>
									</fields>
								</formula>
							</page>
							<page>
								<text align="center" top="20">
									<h>Tak for det</h>
									<p>Du vil hurtigst muligt blive kontaktet med et svar.</p>
								</text>
								<buttons align="center" small="true">
									<button text="OK" click="feedbackPages.previous()"/>
								</buttons>
							</page>
						</pages>
					</overflow>
				</tile>
				<tile width="40" height="50" top="50" left="60" variant="light" name="helpTile">
					<actions>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>{Help ; da: Hjælp}</title>
					<div class="help">
						<columns>
							<column>
								<icon icon="common/lifebuoy" size="64" text="{User manual ; da: Brugervejledning}" name="userManual"/>
							</column>
							<column>
								<icon icon="common/stamp" size="64" text="{Contact the developers ; da : Kontakt udviklerne}" name="contact"/>
							</column>
						</columns>
					</div>
				</tile>
			</tiles>
		</div>
	</div>
	
	<boundpanel name="settingsPanel" variant="light" width="200" padding="10" modal="true">
		<formula name="settingsFormula">
			<fields>
				<field label="{Language; da: Sprog}">
					<dropdown key="language" value="'.InternalSession::getLanguage().'">
						<item title="{Danish; da: Dansk}" value="da"/>
						<item title="{English; da: Engelsk}" value="en"/>
					</dropdown>
				</field>
				<field label="{Password ; da: Kode}">
					<button text="{Change password; da:Skift kodeord}" variant="paper" mini="true" name="changePassword"/>
				</field>
			</fields>
		</formula>
		<buttons align="right">
			<button variant="paper" text="OK" small="true" name="saveSettings"/>
		</buttons>
	</boundpanel>
	
	<box title="{Change password; da:Skift kode}" closable="true" name="passwordBox" absolute="true" width="400" modal="true" padding="10">
		<formula name="passwordFormula">
			<fields>
				<field label="{Existing password; da: Nuværende kode}">
					<text-input key="old" secret="true"/>
				</field>
				<field label="{New password; da: Ny kode}">
					<text-input key="password" secret="true"/>
				</field>
				<field label="{New password; da: Ny kode igen}">
					<text-input key="password2" secret="true"/>
				</field>
			</fields>
			<buttons align="right">
				<button text="{Cancel; da: Annuller}" name="cancelPassword"/>
				<button text="{Change; da : Skift}" highlighted="true" submit="true" name="submitPassword"/>
			</buttons>
		</formula>
	</box>
</gui>';

In2iGui::render($gui);
?>