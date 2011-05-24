<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Developer" state="frame">
	<controller source="controller.js"/>
	<source name="testsSource" url="ListTests.php"/>
	<layout>
		<top>
		<toolbar>
			<!--divider/-->
		</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="phpInfo" name="selector">
						<item icon="common/info" title="PHP info" value="phpInfo"/>
						<item icon="common/info" title="Session" value="session"/>
						<item icon="common/info" title="Settings" value="settings"/>
						<title>Tests</title>
						<items name="testSelection" source="testsSource"/>
					</selection>
				</overflow>
			</left>
			<center>
				<fragment state="settings" height="full" background="wood">
					<box width="300" top="30" title="Settings" padding="10">
						<formula name="settingsFormula">
							<group>
								<checkbox key="simulateLatency" label="Simulate network latency:" value="'.($_SESSION['core.debug.simulateLatency'] ? 'true' : 'false').'"/>
								<checkbox key="logDatabaseQueries" label="Log database queries:" value="'.($_SESSION['core.debug.logDatabaseQueries'] ? 'true' : 'false').'"/>
							</group>
						</formula>
					</box>
				</fragment>
				<iframe source="PhpInfo.php" name="iframe" state="frame"/>
			</center>
		</middle>
		<bottom/>
	</layout>
</gui>';
In2iGui::render($gui);
?>