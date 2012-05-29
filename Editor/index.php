<?php
/**
 * Displays the base frameset of the internal system
 *
 * @package OnlinePublisher
 * @subpackage Base
 * @category Interface
 */
if (!file_exists('../Config/Setup.php')) {
	header('Location: ../setup/initial/');
	exit;
}
require_once '../Config/Setup.php';
require_once 'Include/Security.php';

$start = 'Services/Start/';
if (Request::exists("page")) {
	$page = Request::getInt('page');
	InternalSession::setPageId($page);
	$start = 'Services/Preview/';
}

$categorized = ToolService::getCategorized();

$lang = InternalSession::getLanguage();

$gui='
<gui xmlns="uri:hui" title="OnlinePublisher editor">
	<source name="searchSource" url="Services/Base/data/Search.php">
		<parameter key="text" value="@search.value"/>
	</source>
	<source name="hierarchySource" url="Services/Base/data/Hierarchy.php"/>
	<source name="issueSource" url="Services/Base/data/ListIssues.php"/>
	<source name="reviewSource" url="Services/Base/data/ListReview.php">
		<parameter key="subset" value="@reviewSubset.value"/>
	</source>
	<controller source="Services/Base/controller.js"/>
	<dock url="'.$start.'" name="dock" position="bottom" frame-name="Desktop">
		<sidebar collapsed="true">
			<bar variant="layout_mini">
				<button icon="monochrome/hierarchy" name="navPages" selected="true"/>
				<button icon="monochrome/note" name="navNotes"/>
				<button icon="monochrome/stamp" name="navReview"/>
				<!--<button icon="monochrome/warning" name="navWarnings"/>-->
			</bar>
			<bar variant="layout" name="searchBar">
				<searchfield adaptive="true" name="search"/>
			</bar>
			<bar variant="layout" name="reviewBar" visible="false">
				<dropdown value="unreviewed" name="reviewSubset">
					<item text="Ikke revideret" value="unreviewed"/>
					<item text="Godkendte" value="accepted"/>
					<item text="Afviste" value="rejected"/>
				</dropdown>
				<!--
				<segmented value="day" name="reviewSpan">
					<item text="Vis alle" value="all"/>
					<item text="Et døgn" value="day"/>
					<item text="7 dage" value="week"/>
				</segmented>
				-->
			</bar>
			<overflow>
				<list name="list" source="searchSource" visible="false"/>
				<selection value="all" name="selector">
					<items source="hierarchySource"/>
				</selection>
			</overflow>
		</sidebar>
		<tabs small="true">';
			$tabs = array('edit'=>'{ Editing ; da: Redigering }','analyse'=>'{Analysis ; da:Analyse}','setup'=>'{ Setup ; da:Opsætning }');
			foreach ($tabs as $tab => $tabTitle) {
				$tools = @$categorized[$tab];
				if ($tools) {
					$gui.='<tab title="'.$tabTitle.'" background="light"><toolbar name="'.$tab.'Toolbar">';
					foreach ($tools as $key => $tool) {
						$deprecated = $tool->key == 'Pages';
						$gui.='<icon title="'.$tool->name->$lang.'" icon="'.$tool->icon.'" click="dock.setUrl(\'Tools/'.$tool->key.'/\')" key="tool:'.$tool->key.'"'.($deprecated ? ' overlay="warning"' : '').'/>';
					}
					$gui.='
					<right>
					<icon title="{ View ; da:Vis }" icon="common/view" click="dock.setUrl(\'Services/Preview/\')" key="service:preview"/>
					<icon title="{ Edit ; da:Rediger }" icon="common/edit" click="dock.setUrl(\'Template/Edit.php/\')" key="service:edit"/>
					<icon title="{ Publish ; da:Udgiv }" icon="common/internet" overlay="upload" click="baseController.goPublish()" key="service:publish"/>
					<!--<divider/>
					<search title="Søgning"/>-->
					<divider/>
					<icon title="Start" icon="common/play" click="dock.setUrl(\'Services/Start/\')" key="service:start"/>
					<icon title="{ Exit ; da: Log ud }" icon="common/stop" click="document.location=\'Authentication.php?logout=true\'"/>
					</right>
					</toolbar></tab>';
				}
			}
			$gui.='
		</tabs>
	</dock>
	
	<boundpanel name="issuePanel" width="250">
		<formula name="issueFormula">
			<fields labels="above">
				<field label="Note:">
					<text-input key="text" multiline="true"/>
				</field>
				<field label="Type">
					<radiobuttons value="improvement" key="kind">
						<item value="improvement" text="Forbedring"/>
						<item value="error" text="Fejl"/>
						<item value="unknown" text="Ukendt"/>
					</radiobuttons>
				</field>
			</fields>
			<buttons>
				<button text="Slet" name="deleteIssue" small="true">
					<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
				</button>
				<button text="Annuller" name="cancelIssue" small="true"/>
				<button text="Gem" highlighted="true" submit="true" small="true" name="saveIssue"/>
			</buttons>
		</formula>
	</boundpanel>
</gui>';

In2iGui::render($gui);
?>
