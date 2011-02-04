<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Services/TemplateService.php';
require_once '../../Classes/Design.php';
require_once '../../Classes/Frame.php';

$designItems='';
$designs = Design::search();
foreach ($designs as $design) {
	$designItems.='<item title="'.In2iGui::escape($design->getTitle()).'" image="../../../style/'.$design->getUnique().'/info/Preview128.png" value="'.$design->getId().'"/>';
}

$frameItems='';
$frames = Frame::search();
foreach ($frames as $frame) {
	$frameItems.='<item icon="common/settings" title="'.In2iGui::escape($frame->getName()).'" value="'.$frame->getId().'"/>';
}

$templateItems='';
$templates = TemplateService::getInstalledTemplates();
foreach ($templates as $template) {
	$info = TemplateService::getTemplateInfo($template['unique']);
	$templateItems.='<item title="'.StringUtils::toUnicode($info['name']).'" image="../../Template/'.$template['unique'].'/thumbnail128.jpg" value="'.$template['id'].'"/>';
}

$gui='
<gui xmlns="uri:In2iGui" title="Sites" padding="10">
	<controller source="controller.js"/>
	<controller source="advanced.js"/>
	<source name="pageListSource" url="ListPages.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="kind" value="@selector.kind"/>
		<parameter key="value" value="@selector.value"/>
	</source>
	<source name="languageSource" url="LanguageItems.php"/>
	<source name="hierarchySource" url="HierarchyItems.php"/>
	<source name="newPageHierarchySource" url="FrameHierarchyItems.php">
		<parameter key="frame" value="@frameSelection.value"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="common/page" title="Ny side" overlay="new" name="newPage"/>
				<divider/>
				<!--<icon icon="common/internet" overlay="upload" title="Udgiv ændringer" action="box.show()"/>-->
				<icon icon="common/edit" title="Rediger" name="edit" disabled="true"/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/view" title="Vis" name="view" disabled="true"/>
				<icon icon="common/delete" title="Slet" name="delete" disabled="true"/>
				<divider/>
				<icon icon="common/page" title="Nyt underpunkt" overlay="new" name="newSubPage" disabled="true"/>
				<right>
					<searchfield title="Søgning" name="search" expandedWidth="200"/>
					<divider/>
					<icon icon="common/settings" title="Avanceret" name="advanced"/>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="all" name="selector">
						<item icon="file/generic" title="Alle sider" value="all"/>
						<title>Hierarkier</title>
						<items source="hierarchySource"/>
						<title>Sprog</title>
						<items source="languageSource"/>
						<title>Oversigter</title>
						<item icon="part/news" title="Nyheder" value="news" kind="subset"/>
						<item icon="common/warning" title="Advarsler" value="warnings"/>
						<item icon="common/edit" title="Ændret" value="changed"/>
						<item icon="common/delete" title="Uden menupunkt" value="nomenu"/>
					</selection>
				</overflow>
			</left>
			<center>
				<overflow>
					<list name="list" source="pageListSource"/>
				</overflow>
			</center>
		</middle>
		<bottom>
			
		</bottom>
	</layout>
	<window name="pageEditor" width="300" title="Side" padding="5">
		<formula name="pageFormula">
			<group labels="above">
				<text key="title" label="Titel:"/>
				<text key="description" label="Beskrivelse:" lines="5"/>
			</group>
			<group>
				<dropdown key="language" label="Sprog:" placeholder="Vælg sprog...">
					<item value="" title="Intet"/>
					<item value="DA" title="Dansk"/>
					<item value="EN" title="Engelsk"/>
					<item value="DE" title="Tysk"/>
				</dropdown>
				<text key="path" label="Sti:"/>
				<checkbox key="searchable" label="Søgbar:"/>
				<checkbox key="disabled" label="Inaktiv:"/>
				<buttons>
					<button name="cancelPage" title="Annuller"/>
					<button name="deletePage" title="Slet"/>
					<button name="savePage" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	<window name="hierarchyItemEditor" width="300" title="Menupunkt" padding="5">
		<formula name="hierarchyItemFormula">
			<group>
				<text key="title" label="Titel:"/>
				<checkbox key="hidden" label="Skjult:"/>
				<buttons>
					<button name="cancelHierarchyItem" title="Annuller"/>
					<button name="deleteHierarchyItem" title="Slet"/>
					<button name="saveHierarchyItem" title="Gem" highlighted="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	<box absolute="true" name="newPageBox" padding="10" modal="true" width="636" variant="textured" title="Oprettelse af ny side" closable="true">
		<wizard name="newPageWizard">
			<step title="Skabelon" icon="file/generic">
				<picker title="Vælg skabelon" name="templatePicker" shadow="true" item-height="128" item-width="96">
				'.$templateItems.'
				</picker>
			</step>
			<step title="Design" icon="common/color">
				<picker title="Vælg design" item-height="128" item-width="128" name="designPicker" shadow="true">
				'.$designItems.'
				</picker>
			</step>
			<step title="Grundopsætning" icon="common/settings" padding="10" frame="true">
				<overflow max-height="200" min-height="160">
					<selection name="frameSelection">
						'.$frameItems.'
					</selection>
				</overflow>
			</step>
			<step title="Menupunkt" padding="10" frame="true" icon="common/hierarchy">
				<overflow height="160">
					<selection name="menuItemSelection">
						<items source="newPageHierarchySource"/>
					</selection>
				</overflow>
				<buttons top="10">
					<button name="noMenuItem" title="Intet menupunkt"/>
				</buttons>
			</step>
			<step title="Egenskaber" padding="10" frame="true" icon="common/info">
				<overflow max-height="200" min-height="160">
				<formula name="newPageFormula">
					<group labels="above">
						<text label="Titel:" name="newPageTitle" key="title"/>
						<text label="Menupunkt:" key="menuItem"/>
						<text label="Sti:" key="path"/>
						<dropdown label="Sprog:" key="language" placeholder="Vælg sprog...">
							<item value="" title="Intet"/>
							<item value="DA" title="Dansk"/>
							<item value="EN" title="Engelsk"/>
							<item value="DE" title="Tysk"/>
						</dropdown>
						<text label="Beskrivelse:" lines="4" key="description"/>
					</group>
				</formula>
				</overflow>
			</step>
		</wizard>
		<buttons top="10" align="right">
			<button title="Forrige" action="newPageWizard.previous()"/>
			<button title="Næste" action="newPageWizard.next()"/>
			<button title="Annuller" action="newPageBox.hide()"/>
			<button title="Opret side" name="createPage" highlighted="true"/>
		</buttons>
	</box>
	<box absolute="true" name="advancedBox" padding="10" modal="true" width="636" variant="textured" title="Avanceret" closable="true">
		<toolbar>
			<icon icon="common/page" title="Specielle sider" selected="true" name="advancedSpecialPages"/>
			<icon icon="common/page" title="Rammer" name="advancedFrames"/>
			<icon icon="common/page" title="Skabeloner" name="advancedTemplates"/>
		</toolbar>
		<iframe height="300" name="advancedFrame"/>
	</box>
</gui>';
In2iGui::render($gui);
?>