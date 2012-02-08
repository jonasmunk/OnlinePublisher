<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="System">
	<controller source="controller.js"/>
	<source name="listSource" url="data/ListIssues.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="kind" value="@selector.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="common/info" title="Info" name="info"/>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection value="overview" name="selector">
					<item icon="view/list" title="Oversigt" value="overview"/>
				</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="listSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</layout>

	<window title="Sag" name="issueWindow" icon="common/folder" width="300" padding="5">
		<formula name="issueFormula">
			<group labels="above">
				<text label="Titel" key="title"/>
				<text label="Tekst" key="note" multiline="true"/>
				<dropdown label="Type" key="kind">
					'.GuiUtils::buildTranslatedItems(IssueService::getKinds()).'
				</dropdown>
				<buttons>
					<button name="cancelIssue" title="Annuller"/>
					<button name="deleteIssue" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet opgaven" cancel="Nej"/>
					</button>
					<button name="saveIssue" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
</gui>';

In2iGui::render($gui);
?>