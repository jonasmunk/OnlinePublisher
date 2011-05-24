<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System">
	<controller source="controller.js"/>
	<source name="listSource" url="List.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="kind" value="@selector.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<!--icon icon="common/edit" title="Rediger"/>
				<icon icon="common/info" title="Egenskaber"/-->
			</toolbar>
		</top>
		<middle>
			<left>
				<selection value="warnings" name="selector">
					<item icon="common/warning" title="Advarsler" value="warnings"/>
					<title>Kilder</title>
					<item icon="common/search" title="Søgeindeks" value="index"/>
					<item icon="common/info" title="Unikke ord" value="words"/>
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