<?php
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Services/PageService.php';

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
				<icon icon="common/link" title="{da:Rediger links;en:Edit links}" overlay="edit" name="editLinks"/>
			</toolbar>
		</tab>
		<tab title="{da:Avanceret;en:Advanced}" background="light">
			<toolbar>
				<icon icon="common/time" title="{da:Historik;en:History}" name="history"/>
			</toolbar>
		</tab>
	</tabs>
</gui>';
In2iGui::render($gui);
?>