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
	<source name="commitFeed" url="CommitFeed.php"/>
	<source name="newsFeed" url="NewsFeedArticles.php"/>
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
					<list source="taskSource" name="taskList"/>
				</tile>
				<tile width="30" height="100" top="0" left="30" background="#396" name="developmentTile">
					<actions>
						<icon icon="monochrome/round_arrow_left" key="previous"/>
						<icon icon="monochrome/round_arrow_right" key="next"/>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>Udvikling</title>
					<pages name="developmentPages">
						<page>
							<list source="newsFeed" name="newsList" variant="white" selectable="false"/>
						</page>
						<page>
							<list source="developerFeed" variant="white" selectable="false"/>
						</page>
						<page>
							<list source="commitFeed" variant="white" selectable="false"/>
						</page>
						<page>
							<div style="color: #fff; font-family: \'Helvetica Neue\'; padding: 0 10px; font-size: 14px; line-height: 18px; font-weight: 100;">
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
							</div>
						</page>
					</pages>
				</tile>
				<tile width="40" height="50" top="0" left="60" background="#a3a">
					<actions>
						<icon icon="monochrome/info"/>
						<icon icon="monochrome/edit" key="edit"/>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>Statistik</title>
					<list source="warningsList"/>
				</tile>
				<tile width="40" height="50" top="50" left="60" background="#399">
					<actions>
						<icon icon="monochrome/info"/>
						<icon icon="monochrome/edit" key="edit"/>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>Hjælp</title>
					<list source="warningsList"/>
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