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
	<structure>
		<top>
			<toolbar>
				<icon icon="common/close" text="{Close; da:Luk}" name="close"/>
				<divider/>
				<icon icon="common/refresh" text="{Reconstruct; da:Gendan}" name="reconstruct" disabled="true"/>
			</toolbar>
		</top>
		<middle>
			<left>
				<overflow>
				<selection name="selector" value="list">
					<item icon="view/list" title="{Overview; da:Oversigt}" value="list"/>
					<title>{Versions: da:Versioner}</title>
					<items source="selectionSource"/>
				</selection>
				</overflow>
			</left>
			<center>
				<bar state="viewer" variant="layout">
					<text name="viewerHeader" variant="header"/>
					<right>
						<button small="true" name="closeViewer" text="{Close; da:Luk}"/>
					</right>
				</bar>
				<overflow state="viewer">
				<iframe name="viewerFrame"/>
				</overflow>
				<overflow state="list">
					<list name="list" source="listSource"/>
				</overflow>
			</center>
		</middle>
		<bottom/>
	</structure>
	
	<boundpanel name="messagePanel" width="300">
		<formula name="messageFormula">
			<fields labels="above">
				<field>
					<text-input multiline="true" key="message" value="animate-value-change"/>
				</field>
			</fields>
			<buttons>
				<button text="{Cancel; da:Annuller}" name="cancelMessage"/>
				<button text="{Save; da:Gem}" submit="true" highlighted="true"/>
			</buttons>
		</formula>
	</boundpanel>
	
</gui>';

In2iGui::render($gui);
?>