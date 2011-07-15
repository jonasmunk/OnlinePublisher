<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Developer" state="frame">
	<controller source="controller.js"/>
	<source name="testsSource" url="data/ListTests.php"/>
	<source name="graphSource" url="data/GraphData.php"/>
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
						<item icon="common/settings" title="Graph" value="graph"/>
						<title>Tests</title>
						<items name="testSelection" source="testsSource"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow state="settings">
					<fragment height="full" background="wood">
						<box width="300" top="30" title="Settings" padding="10">
							<formula name="settingsFormula">
								<group>
									<checkbox key="simulateLatency" label="Simulate network latency:" value="'.($_SESSION['core.debug.simulateLatency'] ? 'true' : 'false').'"/>
									<checkbox key="logDatabaseQueries" label="Log database queries:" value="'.($_SESSION['core.debug.logDatabaseQueries'] ? 'true' : 'false').'"/>
								</group>
							</formula>
						</box>
					</fragment>
				</overflow>
				<bar variant="layout" state="graph">
					<button small="true" text="Test"/>
				</bar>
				<overflow state="graph">
					<graph source="graphSource" name="graph" layout="d3"/>
				</overflow> 
				<iframe source="data/PhpInfo.php" name="iframe" state="frame"/>
			</center>
		</middle>
		<bottom/>
	</layout>
</gui>';
In2iGui::render($gui);
?>