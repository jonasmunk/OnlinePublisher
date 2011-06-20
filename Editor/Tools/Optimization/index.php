<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System" state="overview">
	<controller source="controller.js"/>
	<source name="listSource" url="data/List.php">
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
				<selection value="overview" name="selector">
					<item icon="common/info" title="Oversigt" value="overview"/>
					<item icon="common/warning" title="Advarsler" value="warnings"/>
					<title>Sprog</title>
					<item icon="common/search" title="Søgeindeks" value="index"/>
					<item icon="common/info" title="Unikke ord" value="words"/>
				</selection>
			</left>
			<middle>
				<overflow>
					<bar state="list" variant="layout">
						<text name="listDescription"/>
					</bar>
					<list name="list" source="listSource" state="list"/>
					<fragment state="overview" background="brushed" height="full">
						<box width="500" padding="10" top="20">
							<formula>
								<group labels="above">
									<text label="Hvad er hjemmesidens formål:" multiline="true"/>
									<tokens label="Ord der skal være på siden:"/>
									<text label="Hvilke målgrupper har siden:" multiline="true"/>
								</group>
							</formula>
						</box>
					</fragment>
				</overflow>
			</middle>
		</middle>
		<bottom/>
	</layout>
</gui>';

In2iGui::render($gui);
?>