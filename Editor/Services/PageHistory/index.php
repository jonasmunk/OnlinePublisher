<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../Include/Private.php';

$pageId = InternalSession::getPageId();

$gui='

<gui xmlns="uri:hui" padding="10" title="History" state="list">
	<controller source="js/controller.js"/>
	<source name="listSource" url="data/HistoryList.php">
		<parameter key="pageId" value="'.$pageId.'"/>
	</source>
	<source name="selectionSource" url="data/Items.php">
		<parameter key="pageId" value="'.$pageId.'"/>
	</source>
	<layout>
		<top>
			<toolbar>
				<icon icon="common/close" text="Luk" name="close"/>
				<divider/>
				<icon icon="common/refresh" text="Gendan" name="reconstruct" disabled="true"/>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection name="selector" value="list">
					<item icon="view/list" title="Oversigt" value="list"/>
					<title>Versioner</title>
					<items source="selectionSource"/>
				</selection>
				</overflow>
			</left>
			<middle>
				<bar state="viewer" variant="layout">
					<text name="viewerHeader" variant="header"/>
					<right>
						<button small="true" name="closeViewer" text="Luk"/>
					</right>
				</bar>
				<overflow state="viewer">
				<iframe name="viewerFrame"/>
				</overflow>
				<overflow state="list">
					<list name="list" source="listSource"/>
				</overflow>
			</middle>
		</middle>
		<bottom/>
	</layout>
	
	<boundpanel name="messagePanel" width="300">
		<formula name="messageFormula">
			<group labels="above">
				<text multiline="true" key="message" value="animate-value-change"/>
			</group>
			<buttons>
				<button text="Annuller" name="cancelMessage"/>
				<button text="Gem" submit="true" highlighted="true"/>
			</buttons>
		</formula>
	</boundpanel>
	
</gui>';

In2iGui::render($gui);
?>