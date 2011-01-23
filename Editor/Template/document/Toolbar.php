<?php
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Link.php';

if (Request::getBoolean('link')) {
	$id = Request::getInt('id');
	$link = Link::load($id);
	$new = $link==null;
	if (!$link) {
		$link = new Link();
	}
	$gui='
	<gui xmlns="uri:In2iGui" title="Dokument">
		<controller source="js/LinkToolbar.js"/>
		<script>
		controller.id='.$id.';
		</script>
		<tabs small="true" below="true">
			<tab title="'.($new ? '{da:Nyt link;en:New link}' : '{da:Rediger link;en:Edit link}').'" background="light">
				<toolbar>
					<grid left="10">
						<row>
							<cell label="{da:Tekst:;en:Text:}" width="200" right="10">
								<textfield name="text" value="'.In2iGui::escape($link->getText()).'"/>
							</cell>
							<cell label="{da:Side:;en:Page:}" width="200" right="10">
								<dropdown name="page" adaptive="true" value="'.$link->getPage().'">
									'.GuiUtils::buildPageItems().'
								</dropdown>
							</cell>
							<cell label="URL:" width="100">
								<textfield name="url" value="'.In2iGui::escape($link->getUrl()).'"/>
							</cell>
							<cell left="10">
							'.($new ? '
								<button title="{da:Opret;en:Create}" small="true" rounded="true" name="create"/>
							' : '
								<button title="{da:Opdater;en:Update}" small="true" rounded="true" name="update"/>
								<button title="{da:Slet;en:Delete}" small="true" rounded="true" name="delete">
									<confirm text="{da:Vil du slette linket?;en:Really delete this link?}" ok="{da:Ja, slet;en:Yes, delete}" cancel="{da:Nej;en:No}"/>
								</button>
							').'
							</cell>
						</row>
						<row>
							<cell label="{da:Beskrivelse:;en:Description:}" right="10">
								<textfield name="alternative" value="'.In2iGui::escape($link->getAlternative()).'"/>
							</cell>
							<cell label="{da:Fil:;en:File:}" width="200" right="10">
								<dropdown name="file" adaptive="true" value="'.$link->getFile().'">
									'.GuiUtils::buildObjectItems('file').'
								</dropdown>
							</cell>
							<cell label="E-mail:" width="100">
								<textfield name="email" value="'.In2iGui::escape($link->getEmail()).'"/>
							</cell>
							<cell left="10">
								<button title="{da:Annuller;en:Cancel}" click="document.location=\'Toolbar.php\'" small="true" rounded="true"/>
							</cell>
						</row>
					</grid>
				</toolbar>
			</tab>
		</tabs>
	</gui>';
} else {
	$gui='
	<gui xmlns="uri:In2iGui" title="Dokument">
		<controller source="js/Toolbar.js"/>
		<script>
		controller.pageId='.InternalSession::getPageId().';
		</script>
		<tabs small="true" below="true">
			<tab title="{da:Dokument ; en:Document}" background="light">
				<toolbar>
					<icon icon="common/close" title="{da: Luk ; en: Close}" name="close"/>
					<divider/>
					<icon icon="common/internet" overlay="upload" title="{da:Udgiv;en:Publish}" name="publish" disabled="'.(Page::isChanged(InternalSession::getPageId()) ? 'false' : 'true').'"/>
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
}
In2iGui::render($gui);
?>