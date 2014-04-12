<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Developer" state="settings">
	<controller source="controller.js"/>
	<source name="testsSource" url="data/TestSelection.php"/>
	<source name="graphSource" url="data/GraphData.php"/>
	<source name="diagramSource" url="data/DiagramData.php">
		<parameter key="parent" value="@diagramSubset.value"/>
	</source>
	<source name="classesSource" url="data/ListClasses.php"/>
	<structure>
		<top>
		<toolbar>
			<!--divider/-->
		</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="settings" name="selector" top="5">
						<item icon="common/info" title="PHP info" value="phpInfo"/>
						<item icon="common/time" title="Session" value="session"/>
						<item icon="common/time" title="Report" value="report"/>
						<item icon="common/tools" title="Settings" value="settings"/>
						<item icon="common/object" title="Classes" value="classes"/>
						<title>UI</title>
						<item icon="common/search" title="Finders" value="finders"/>
						<item icon="monochrome/nuclear" title="Graph" value="graph"/>
						<item icon="common/hierarchy" title="Diagram" value="diagram"/>
						<title>Tests</title>
						<items name="testSelection" source="testsSource"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow state="list">
					<list name="list" source="classesSource"/>
				</overflow>
				<overflow state="settings">
					<fragment height="full" background="wood">
						<box width="300" top="30" title="Settings" padding="10">
							<formula name="settingsFormula">
								<button text="Rebuild classes" name="rebuildClasses"/>
								<fields>
									<field label="Simulate network latency:">
										<checkbox key="simulateLatency" value="'.(@$_SESSION['core.debug.simulateLatency'] ? 'true' : 'false').'"/>
									</field>
									<field label="Log database queries:">
										<checkbox key="logDatabaseQueries" value="'.(@$_SESSION['core.debug.logDatabaseQueries'] ? 'true' : 'false').'"/>
									</field>
								</fields>
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
				<bar variant="layout" state="diagram">
					<segmented value="Entity" name="diagramSubset" variant="inset">
						<item value="all" text="All"/> 
						<item value="Entity" text="Entity"/> 
						<item value="Object" text="Objects"/> 
						<item value="Part" text="Parts"/> 
						<item value="TemplateController" text="Template controllers"/> 
					</segmented>
					<right>
						<button text="Play" name="playDiagram" small="true"/>
						<button text="Expand" name="expandDiagram" small="true"/>
						<button text="Contract" name="contractDiagram" small="true"/>
					</right>
				</bar>
				<overflow state="diagram">
					<diagram source="diagramSource" name="diagram"/>
				</overflow> 
				<iframe source="data/PhpInfo.php" name="iframe" state="frame"/>
			</center>
		</middle>
		<bottom/>
	</structure>
</gui>';
In2iGui::render($gui);
?>