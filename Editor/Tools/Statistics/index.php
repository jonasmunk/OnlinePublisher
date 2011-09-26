<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System">
	<controller source="controller.js"/>
	<source name="listSource" url="List.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="kind" value="@selector.value"/>
		<parameter key="time" value="@time.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="common/edit" title="Rediger" disabled="true"/>
			</toolbar>
		</top>
		<middle>
			<left>
				<selection name="selector" value="pages">
					<item icon="common/page" title="Sider" value="pages"/>
					<item icon="common/page" title="Sidestier" value="pagePath"/>
					<title>Teknik</title>
					<item icon="common/settings" title="Programmer" value="browsers"/>
					<item icon="common/settings" title="Programversioner" value="browserVersions"/>
				</selection>
				<selection name="time" value="always">
					<title>Tid</title>
					<item icon="common/time" title="Altid" value="always"/>
					<item icon="common/time" title="Seneste år" value="year"/>
					<item icon="common/time" title="Seneste måned" value="month"/>
					<item icon="common/time" title="Seneste uge" value="week"/>
				</selection>
			</left>
			<middle>
				<overflow>
					<list name="list" source="listSource"/>
				</overflow>
			</middle>
		</middle>
		<bottom/>
	</layout>
</gui>';

In2iGui::render($gui);
?>