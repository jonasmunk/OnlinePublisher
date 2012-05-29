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
	<structure>
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
	</structure>

	<window title="Sag" name="issueWindow" icon="common/folder" width="300" padding="5">
		<formula name="issueFormula">
			<fields labels="above">
				<field label="Titel">
					<text-input key="title"/>
				</field>
				<field label="Tekst">
					<text-input key="note" multiline="true"/>
				</field>
				<field label="Type">
					<dropdown key="kind">
						'.GuiUtils::buildTranslatedItems(IssueService::getKinds()).'
					</dropdown>
				</field>
				<buttons>
					<button name="cancelIssue" title="Annuller"/>
					<button name="deleteIssue" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet opgaven" cancel="Nej"/>
					</button>
					<button name="saveIssue" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
</gui>';

In2iGui::render($gui);
?>