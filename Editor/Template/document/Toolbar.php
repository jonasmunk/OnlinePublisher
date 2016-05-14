<?php
require_once '../../Include/Private.php';

$changed = PageService::isChanged(InternalSession::getPageId());

$gui='
<gui xmlns="uri:hui" title="Dokument">
	<controller source="js/Toolbar.js"/>
	<script>
	controller.pageId='.InternalSession::getPageId().';
	</script>
	<tabs small="true" below="true">
		<tab title="{da:Dokument ; en:Document}" background="light">
			<toolbar>
				<icon icon="common/close" title="{da: Luk ; en: Close}" name="close"/>
				<divider/>
				<icon icon="common/internet" overlay="upload" title="{da:Udgiv;en:Publish}" name="publish" disabled="'.($changed ? 'false' : 'true').'"/>
				<icon icon="common/view" title="{da:Vis;en:View}" name="preview"/>
				<icon icon="common/info" title="{da:Info;en:Info}" name="properties"/>
				<divider/>
				<icon icon="common/link" title="{da:Nyt link;en:New link}" overlay="new" name="newLink"/>
				<icon icon="common/link" title="{da:Oversigt;en:Edit links}" overlay="view" name="editLinks"/>
				<!--
				<more text="{More...;da:Mere...}">
					<divider/>
					<icon icon="file/generic" title="{Export;da:Eksportér}" overlay="download" name="export"/>
					<icon icon="file/generic" title="{Import;da:Importér}" overlay="upload" name="import"/>
				</more>
				-->
			</toolbar>
		</tab>
	</tabs>
</gui>';
UI::render($gui);
?>