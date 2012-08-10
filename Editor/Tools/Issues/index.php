<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Issues">
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
					<item icon="view/list" title="{Overview; da:Oversigt}" value="overview"/>
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

	<window title="{Issue; da:Sag}" name="issueWindow" icon="common/folder" width="300" padding="5">
		<formula name="issueFormula">
			<fields labels="above">
				<field label="{Title; da:Titel}">
					<text-input key="title"/>
				</field>
				<field label="{Text; da:Tekst}">
					<text-input key="note" multiline="true"/>
				</field>
				<field label="Type">
					<dropdown key="kind">
						'.GuiUtils::buildTranslatedItems(IssueService::getKinds()).'
					</dropdown>
				</field>
				<buttons>
					<button name="cancelIssue" title="{Cancel; da:Annuller}"/>
					<button name="deleteIssue" title="{Delete; da:Slet}">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete issue; da:Ja, slet sagen}" cancel="{No; da:Nej}"/>
					</button>
					<button name="saveIssue" title="{Save; da:Gem}" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
</gui>';

In2iGui::render($gui);
?>