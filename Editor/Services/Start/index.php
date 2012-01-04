<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/SystemInfo.php';

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
		</div>
		<div style="position: absolute; top: 76px; bottom: 5px; left: 5px; right: 5px; overflow: hidden;">
			<tiles space="10">
				<tile width="30" height="100" top="0" left="0" variant="light" name="taskTile">
					<actions>
						<icon icon="monochrome/info"/>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>Opgaver</title>
					<overflow>
					<list source="taskSource" name="taskList" indent="10" selectable="false">
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
				<tile width="30" height="100" top="0" left="30" variant="light" name="developmentTile"><!--background="#396" -->
					<actions>
						<icon icon="monochrome/round_arrow_left" key="previous"/>
						<icon icon="monochrome/round_arrow_right" key="next"/>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>Udvikling</title>
					<pages name="developmentPages" height="full">
						<page>
							<overflow>
								<list source="newsFeed" name="newsList" selectable="false" indent="10"/>
							</overflow>
						</page>
						<page>
							<overflow>
								<list source="developerFeed" selectable="false" indent="10"/>
							</overflow>
						</page>
						<page>
							<overflow>
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
					<overflow>
					<pages name="feedbackPages">
						<page>
							<formula padding="10" name="feedbackForm">
								<group labels="above">
									<text multiline="true" label="Skriv til os med ris, ros eller spørgsmål" key="message"/>
									<buttons>
										<button text="Send" submit="true" name="sendFeedback"/>
									</buttons>
								</group>
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
				<tile width="40" height="50" top="50" left="60" variant="light">
					<actions>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>Hjælp</title>
					<div class="help">
						<columns>
							<column>
								<a href="javascript://" class="buoy" onclick="window.open(\'http://www.in2isoft.dk/support/onlinepublisher/\')">
									<span style=""></span>
									<strong>Brugervejledning</strong>
								</a>
							</column>
							<column>
								<a href="javascript://" class="stamp" onclick="window.open(\'http://www.in2isoft.dk/kontakt/\')">
									<span style=""></span>
									<strong>Kontakt udviklerne</strong>
								</a>
							</column>
						</columns>
					</div>
				</tile>
			</tiles>
		</div>
	</div>
	<script>
		hui.ui.listen({
			$clickIcon : function(info) {
				if (info.key=="expand") {
					info.tile.toggleFullScreen();
				}
			},
			$clickIcon$developmentTile : function(info) {
				if (info.key=="expand") {
					info.tile.toggleFullScreen();
				}
				else if (info.key=="next") {
					developmentPages.next();
				} else if (info.key=="previous") {
					developmentPages.previous();
				}
			}
		})
	</script>
</gui>';

In2iGui::render($gui);
?>