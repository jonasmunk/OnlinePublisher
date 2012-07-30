<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$simulate = Request::getBoolean('simulate');

$designItems='';
$designs = Query::after('design')->get();

if ($simulate) {
	$designs = array($designs[0]);
}

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
	<item value="" title="{None;da:Intet}"/>
	<item value="DA" title="{Danish;da:Dansk}"/>
	<item value="EN" title="{English;da:Engelsk}"/>
	<item value="DE" title="{German;da:Tysk}"/>
';

$gui='
<gui xmlns="uri:hui" title="Sites" padding="10">
	<controller source="controller.js"/>
	<controller source="hierarchy.js"/>
	<controller source="advanced.js"/>
	<source name="pageListSource" url="data/ListPages.php">
		<parameter key="windowPage" value="@list.window.page"/>
		<parameter key="sort" value="@list.sort.key"/>
		<parameter key="direction" value="@list.sort.direction"/>
		<parameter key="query" value="@search.value"/>
		<parameter key="kind" value="@selector.kind"/>
		<parameter key="value" value="@selector.value"/>
		<parameter key="reviewSpan" value="@reviewSpan.value"/>
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
	<structure>
		<top>
			<toolbar>
				<icon icon="common/page" title="{New page;da:Ny side}" overlay="new" name="newPage"/>
				<icon icon="common/hierarchy_item" title="{New point;da:Nyt punkt}" overlay="new" name="newHierarchyItem" disabled="true"/>
				<icon icon="common/hierarchy" title="{New hierarchy;da:Nyt hierarki}" overlay="new" name="newHierarchy"/>
				<divider/>
				<!--<icon icon="common/internet" overlay="upload" title="Udgiv ændringer" click="box.show()"/>-->
				<icon icon="common/edit" title="{Edit ; da:Rediger}" name="edit" disabled="true"/>
				<icon icon="common/info" title="Info" name="info" disabled="true"/>
				<icon icon="common/view" title="{Show ; da:Vis}" name="view" disabled="true"/>
				<icon icon="common/delete" title="{Delete;da:Slet}" name="delete" disabled="true">
					<confirm text="{Are you sure? It cannot be undone; da:Er du sikker? Det kan ikke fortrydes!}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel;da:Annuller}"/>
				</icon>
				<right>
					<field label="{Search; da:Søgning}">
						<searchfield name="search" expanded-width="200"/>
					</field>
					<divider/>
					<icon icon="common/settings" title="{Advanced;da:Avanceret}" name="advanced"/>
				</right>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
					<selection value="all" name="selector">
						<item icon="common/page" title="{All pages; da:Alle sider}" value="all"/>
						<item icon="common/time" title="{Latest; da:Seneste}" value="latest"/>
						<title>{Hierarchies;da:Hierarkier}</title>
						<items source="hierarchySource"/>
						<title>{Language; da:Sprog}</title>
						<items source="languageSource"/>
						<title>Oversigter</title>
						<item icon="monochrome/news" title="{News ; da:Nyheder}" value="news" kind="subset"/>
						<item icon="monochrome/warning" title="{Warnings ; da:Advarsler}" value="warnings" kind="subset"/>
						<item icon="monochrome/edit" title="{Changed ; da:Ændret}" value="changed" kind="subset"/>
						<item icon="monochrome/delete" title="{No menu item ; da:Uden menupunkt}" value="nomenu" kind="subset"/>
						<item icon="monochrome/stamp" title="{Review ; da:Revidering}" value="review" kind="subset"/>
					</selection>
				</overflow>
			</left>
			<center>
				<bar name="reviewBar" variant="layout" visible="false">
					<text text="{Shows a list of page reviews; da:Her vises en oversigt over revidering af sider}"/>
					<right>
					<segmented value="day" name="reviewSpan">
						<item text="{Show all; da:Vis alle}" value="all"/>
						<item text="{24 hours; da:Et døgn}" value="day"/>
						<item text="{7 days; da:7 dage}" value="week"/>
					</segmented>
					</right>
				</bar>
				<overflow>
					<list name="list" source="pageListSource" variant="light"/>
				</overflow>
			</center>
		</middle>
		<bottom>
			
		</bottom>
	</structure>
	
	<window name="pageEditor" width="400" title="{Page ; da:Side}" icon="common/page">
		<toolbar variant="window">
			<icon icon="common/info" text="Info" selected="true" name="pageInfo"/>
			<!--
			<icon icon="common/settings" text="Avanceret" click="pageEditor.flip()"/>
			-->
			<icon icon="common/flag" text="{Language; da:Sprog}" name="pageTranslation"/>
			<right>
			<icon icon="common/internet" text="{Publish; da:Udgiv}" overlay="upload" name="publishPage" disabled="true"/>
			<icon icon="common/edit" text="{Edit ; da:Rediger}" name="editPage"/>
			<icon icon="common/view" text="{Show ; da:Vis}" name="viewPage"/>
			</right>
		</toolbar>
		<fragment name="pageInfoFragment">
			<formula name="pageFormula" padding="5">
				<fields labels="above">
					<field label="{Title ; da: Titel}:">
						<text-input key="title"/>
					</field>
					<field label="{Description; da:Beskrivelse}:">
						<text-input key="description" multiline="true"/>
					</field>
				</fields>
				<fields>
					<field label="{Language; da:Sprog}:">
						<dropdown key="language" placeholder="Vælg sprog...">
							'.$languageItems.'
						</dropdown>
					</field>
					<field label="Design:">
						<dropdown key="designId">
							'.$designItems.'
						</dropdown>
					</field>
					<field label="{Setup; da:Opsætning}:">
						<dropdown key="frameId">
							'.$frameItems.'
						</dropdown>
					</field>
					<field label="{Path; da:Sti}:">
						<text-input key="path"/>
					</field>
					<field label="{Searchable; da:Søgbar}:">
						<checkbox key="searchable"/>
					</field>
					<field label="{Inactive; da:Inaktiv}:">
						<checkbox key="disabled"/>
					</field>
					<buttons>
						<button name="cancelPage" title="{Cancel; da:Annuller}"/>
						<button name="deletePage" title="{Delete; da:Slet}">
							<confirm text="{Are you sure? It cannot be undone! ; da:Er du sikker? Det kan ikke fortrydes!}" ok="{Yes, delete page; da:Ja, slet side}" cancel="{No; da:Nej}"/>
						</button>
						<button name="savePage" title="{Save; da:Gem}" highlighted="true"/>
					</buttons>
				</fields>
			</formula>
		</fragment>
		<fragment name="pageTranslationFragment" visible="false">
		<!--
			<bar>
				<button icon="common/new" text="Tilføj oversættelse" name="addTranslation"/>
			</bar>-->
			<list name="pageTranslationList" selectable="false"/>
			<buttons top="5" left="5" bottom="3">
				<button text="{Add translation; da:Tilføj oversættelse}" highlighted="true" small="true" name="addTranslation"/>
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
			<fields>
				<field label="Titel:">
					<text-input key="name"/>
				</field>
				<field label="Sprog:">
					<dropdown key="language" placeholder="Vælg sprog...">
						'.$languageItems.'
					</dropdown>
				</field>
			</fields>
			<fields>
				<buttons>
					<button name="cancelHierarchy" title="Annuller"/>
					<button name="deleteHierarchy" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet hierarki" cancel="Nej"/>
					</button>
					<button name="saveHierarchy" title="Gem" highlighted="true" submit="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<window name="hierarchyItemEditor" width="300" title="Menupunkt" padding="5">
		<formula name="hierarchyItemFormula">
			<fields>
				<field label="Titel:">
					<text-input key="title"/>
				</field>
				<field label="Skjult:">
					<checkbox key="hidden"/>
				</field>
			</fields>
			<fieldset legend="Link">
				<fields>
					<field label="Side:">
						<dropdown key="page" source="pagesSource" name="hierarchyItemPage"/>
					</field>
					<field label="Reference">
						<checkbox key="reference" name="hierarchyItemReference"/>
					</field>
					<field label="Fil:">
						<dropdown key="file" source="filesSource" name="hierarchyItemFile"/>
					</field>
					<field label="URL:">
						<text-input key="url" name="hierarchyItemURL"/>
					</field>
					<field label="E-post:">
						<text-input key="email" name="hierarchyItemEmail"/>
					</field>
				</fields>				
			</fieldset>
			<fields>
				<buttons>
					<button name="cancelHierarchyItem" title="Annuller"/>
					<button name="deleteHierarchyItem" title="Slet">
						<confirm text="Er du sikker?" ok="Ja, slet punkt" cancel="Annuller"/>
					</button>
					<button name="saveHierarchyItem" title="Gem" highlighted="true"/>
				</buttons>
			</fields>
		</formula>
	</window>
	
	<box absolute="true" name="newPageBox" padding="10" modal="true" width="636" variant="textured" title="Oprettelse af ny side" closable="true">
		<wizard name="newPageWizard">
			<step title="Skabelon" icon="file/generic">
				<picker title="Vælg skabelon" name="templatePicker" shadow="true" item-height="128" item-width="96">
				'.$templateItems.'
				</picker>
			</step>
			<step title="Design" key="design" icon="common/color">
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
					<fields labels="above">
						<field label="Titel:">
							<text-input name="newPageTitle" key="title"/>
						</field>
						<field label="Menupunkt:">
							<text-input key="menuItem"/>
						</field>
						<field label="Sti:">
							<text-input key="path"/>
						</field>
						<field label="Sprog:">
							<dropdown key="language" placeholder="Vælg sprog...">
								'.$languageItems.'
							</dropdown>
						</field>
						<field label="Beskrivelse:">
							<text-input multiline="true" key="description"/>
						</field>
					</fields>
				</formula>
				</overflow>
			</step>
		</wizard>-->
		<buttons top="10" align="right">
			<button title="Forrige" name="newPagePrevious"/>
			<button title="Næste" name="newPageNext"/>
			<button title="Annuller" name="newPageCancel"/>
			<button title="Opret side" name="createPage" highlighted="true"/>
		</buttons>
	</box>
	
	<box absolute="true" name="advancedBox" modal="true" width="636" variant="textured" title="Avanceret" closable="true">
		<toolbar>
			<icon icon="common/page" title="Specielle sider" selected="true" name="advancedSpecialPages"/>
			<icon icon="common/page" title="Rammer" name="advancedFrames"/>
			<icon icon="common/page" title="Skabeloner" name="advancedTemplates"/>
		</toolbar>
		<iframe height="400" name="advancedFrame"/>
	</box>
	
	<boundpanel name="previewer" width="300" variant="light" modal="true" padding="5">
		<iframe name="previewFrame" height="400"/>
	</boundpanel>
</gui>';
In2iGui::render($gui);
?>