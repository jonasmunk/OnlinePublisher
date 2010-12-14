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
		<overflow vertical="109">
			<space all="25">
			<columns space="30">
				<column>
					<header icon="monochrome/gear">Udvikling</header>
					<articles source="developerFeed"/>
				</column>
				<column>
					<header icon="monochrome/gear">Kode</header>
					<articles source="commitFeed"/>
				</column>
				<column>
					<header icon="monochrome/loudspeaker">Nyheder</header>
					<articles source="newsFeed"/>
				</column>
			</columns>
			</space>
		</overflow>
	</box>
</gui>';

In2iGui::render($gui);
?>