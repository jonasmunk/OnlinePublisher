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
	$designItems.='<item title="'.StringUtils::escapeXML($design->getTitle()).'" image="../../../style/'.$design->getUnique().'/info/Preview128.png" value="'.$design->getId().'"/>';
}

$frameItems='';
$frames = Frame::search();
foreach ($frames as $frame) {
	$frameItems.='<item icon="common/settings" title="'.StringUtils::escapeXML($frame->getName()).'" value="'.$frame->getId().'"/>';
}

$templateItems='';
$templates = TemplateService::getInstalledTemplates();
foreach ($templates as $template) {
	$info = TemplateService::getTemplateInfo($template['unique']);
	$templateItems.='<item title="'.StringUtils::toUnicode($info['name']).'" image="../../Template/'.$template['unique'].'/thumbnail128.jpg" value="'.$template['id'].'"/>';
}

$languageItems = '
	<item value="" title="Intet"/>
	<item value="DA" title="Dansk"/>
	<item value="EN" title="Engelsk"/>
	<item value="DE" title="Tysk"/>
';

$gui='
<gui xmlns="uri:hui" title="Sites" padding="10">
	<controller source="controller.js"/>
	<controller source="hierarchy.js"/>
	<controller source="advanced.js"/>
	<source name="pageListSource" url="ListPages.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="kind" value="@selector.kind"/>
		<parameter key="value" value="@selector.value"/>
	</source>
	<source name="pageFinderListSource" url="data/PageFinderList.php">
		<parameter key="windowPage" value="@pageFinderList.window.page"/>
		<parameter key="sort" value="@pageFinderList.sort.key"/>
		<parameter key="direction" value="@pageFinderList.sort.direction"/>
		<parameter key="text" value="@pageFinderSearch.value"/>
	</source>
	<source name="pagesSource" url="../../Services/Model/Items.php?type=page"/>
	<source name="filesSource" url="../../Services/Model/Items.php?type=file"/>
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
				<icon icon="common/delete" title="Slet" name="delete" disabled="true">
					<confirm text="Er du sikker? Det kan ikke fortrydes!" ok="Ja, slet" cancel="Annuller"/>
				</icon>
				<divider/>
				<icon icon="common/hierarchy" title="Nyt hierarki" overlay="new" name="newHierarchy"/>
				<icon icon="common/hierarchy_item" title="Nyt underpunkt" overlay="new" name="newHierarchyItem" disabled="true"/>
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
						<item icon="common/page" title="Alle sider" value="all"/>
						<title>Hierarkier</title>
						<items source="hierarchySource"/>
						<title>Sprog</title>
						<items source="languageSource"/>
						<title>Oversigter</title>
						<item icon="common/news" title="Nyheder" value="news" kind="subset"/>
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
	
	<window name="pageEditor" width="400" title="Side" icon="common/page">
		<toolbar variant="window">
			<icon icon="common/info" text="Info" selected="true" name="pageInfo"/>
			<!--
			<icon icon="common/settings" text="Avanceret" click="pageEditor.flip()"/>
			-->
			<icon icon="common/flag" text="Sprog" name="pageTranslation"/>
			<right>
			<icon icon="common/internet" text="Udgiv" overlay="upload" name="publishPage" disabled="true"/>
			<icon icon="common/edit" text="Rediger" name="editPage"/>
			<icon icon="common/view" text="Vis" name="viewPage"/>
			</right>
		</toolbar>
		<fragment name="pageInfoFragment">
			<formula name="pageFormula" padding="5">
				<group labels="above">
					<text key="title" label="Titel:"/>
					<text key="description" label="Beskrivelse:" lines="5"/>
				</group>
				<group>
					<dropdown key="language" label="Sprog:" placeholder="Vælg sprog...">
						'.$languageItems.'
					</dropdown>
					<text key="path" label="Sti:"/>
					<checkbox key="searchable" label="Søgbar:"/>
					<checkbox key="disabled" label="Inaktiv:"/>
					<buttons>
						<button name="cancelPage" title="Annuller"/>
						<button name="deletePage" title="Slet">
							<confirm text="Er du sikker? Det kan ikke fortrydes!" ok="Ja, slet side" cancel="Nej"/>
						</button>
						<button name="savePage" title="Gem" highlighted="true"/>
					</buttons>
				</group>
			</formula>
		</fragment>
		<fragment name="pageTranslationFragment" visible="false">
		<!--
			<bar>
				<button icon="common/new" text="Tilføj oversættelse" name="addTranslation"/>
			</bar>-->
			<list name="pageTranslationList" selectable="false"/>
			<buttons top="5" left="5" bottom="3">
				<button text="Tilføj oversættelse" highlighted="true" small="true" name="addTranslation"/>
			</buttons>
		</fragment>
		<back>
			<button text="Back" click="pageEditor.flip()"/>
		</back>
	</window>
	
	<window name="pageFinder" width="400" title="Vælg side">
		<searchfield adaptive="true" name="pageFinderSearch"/>
		<overflow max-height="200">
		<list source="pageFinderListSource" name="pageFinderList"/>
		</overflow>
	</window>

	<window name="hierarchyEditor" width="300" title="Hierarki" padding="5" icon="common/hierarchy">
		<formula name="hierarchyFormula">
			<group>
				<text key="name" label="Titel:"/>
				<dropdown key="language" label="Sprog:" placeholder="Vælg sprog...">
					'.$languageItems.'
				</dropdown>
			</group>
			<group>
				<buttons>
					<button name="cancelHierarchy" title="Annuller"/>
					<button name="deleteHierarchy" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet hierarki" cancel="Nej"/>
					</button>
					<button name="saveHierarchy" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</group>
		</formula>
	</window>
	
	<window name="hierarchyItemEditor" width="300" title="Menupunkt" padding="5">
		<formula name="hierarchyItemFormula">
			<group>
				<text key="title" label="Titel:"/>
				<checkbox key="hidden" label="Skjult:"/>
			</group>
			<fieldset legend="Link">
				<group>
					<dropdown key="page" label="Side:" source="pagesSource" name="hierarchyItemPage"/>
					<checkbox key="reference" label="Reference" name="hierarchyItemReference"/>
					<dropdown key="file" label="Fil:" source="filesSource" name="hierarchyItemFile"/>
					<text key="url" label="URL:" name="hierarchyItemURL"/>
					<text key="email" label="E-post:" name="hierarchyItemEmail"/>
				</group>				
			</fieldset>
			<group>
				<buttons>
					<button name="cancelHierarchyItem" title="Annuller"/>
					<button name="deleteHierarchyItem" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet punkt" cancel="Annuller"/>
					</button>
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
					<button name="noMenuItem" title="Intet menupunkt" small="true" rounded="true"/>
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
							'.$languageItems.'
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
	
	<box absolute="true" name="advancedBox" modal="true" width="636" variant="textured" title="Avanceret" closable="true">
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