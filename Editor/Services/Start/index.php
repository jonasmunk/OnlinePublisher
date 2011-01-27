<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/SystemInfo.php';

$gui='
<gui xmlns="uri:In2iGui" padding="10" title="Start">
	<css url="style.css"/>
	<controller name="controller" source="controller.js"/>
	<source name="developerFeed" url="DeveloperFeedArticles.php"/>
	<source name="commitFeed" url="CommitFeed.php"/>
	<source name="newsFeed" url="NewsFeedArticles.php"/>
	<box variant="rounded">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<div class="header">
			<span class="date"> version: '.SystemInfo::getFormattedDate().'</span>
			</div>
		</html>
		<space all="25" bottom="15">
			<columns space="30">
				<column>
					<header icon="monochrome/message">Udvikling</header>
					<overflow vertical="180">
						<articles source="developerFeed"/>
					</overflow>
				</column>
				<column>
					<header icon="monochrome/gear">Kode</header>
					<overflow vertical="180">
						<articles source="commitFeed"/>
					</overflow>
				</column>
				<column>
					<header icon="monochrome/loudspeaker">Nyheder</header>
					<overflow vertical="180">
						<articles source="newsFeed"/>
					</overflow>
				</column>
			</columns>
		</space>
	</box>
</gui>';

In2iGui::render($gui);
?>