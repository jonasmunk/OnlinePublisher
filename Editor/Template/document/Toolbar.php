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
			<tab title="'.($new ? 'Nyt link' : 'Rediger link').'" background="light">
				<toolbar>
					<grid left="10">
						<row>
							<cell label="Tekst:" width="200" right="10">
								<textfield name="text" value="'.In2iGui::escape($link->getText()).'"/>
							</cell>
							<cell label="Side:" width="200" right="10">
								<dropdown name="page" adaptive="true" value="'.$link->getPage().'">
									'.GuiUtils::buildPageItems().'
								</dropdown>
							</cell>
							<cell label="URL:" width="100">
								<textfield name="url" value="'.In2iGui::escape($link->getUrl()).'"/>
							</cell>
							<cell left="10">
							'.($new ? '
								<button title="Opret" small="true" rounded="true" name="create"/>
							' : '
								<button title="Opdater" small="true" rounded="true" name="update"/>
								<button title="Slet" small="true" rounded="true" name="delete">
									<confirm text="Vil du slette linket?" ok="Ja, slet" cancel="Nej"/>
								</button>
							').'
							</cell>
						</row>
						<row>
							<cell label="Beskrivelse:" right="10">
								<textfield name="alternative" value="'.In2iGui::escape($link->getAlternative()).'"/>
							</cell>
							<cell label="Fil:" width="200" right="10">
								<dropdown name="file" adaptive="true" value="'.$link->getFile().'">
									'.GuiUtils::buildObjectItems('file').'
								</dropdown>
							</cell>
							<cell label="E-mail:" width="100">
								<textfield name="email" value="'.In2iGui::escape($link->getEmail()).'"/>
							</cell>
							<cell left="10">
								<button title="Annuller" click="document.location=\'Toolbar.php\'" small="true" rounded="true"/>
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
			<tab title="Dokument" background="light">
				<toolbar>
					<icon icon="common/close" title="Luk" name="close"/>
					<divider/>
					<icon icon="common/internet" overlay="upload" title="Udgiv" name="publish" disabled="'.(Page::isChanged(InternalSession::getPageId()) ? 'false' : 'true').'"/>
					<icon icon="common/view" title="Vis ændringer" name="preview"/>
					<icon icon="common/info" title="Egenskaber" name="properties"/>
					<divider/>
					<icon icon="common/link" title="Nyt link" overlay="new" name="newLink"/>
					<icon icon="common/link" title="Rediger links" overlay="edit" name="editLinks"/>
				</toolbar>
			</tab>
			<tab title="Avanceret" background="light">
				<toolbar>
					<icon icon="common/time" title="Historik" name="history"/>
				</toolbar>
			</tab>
		</tabs>
	</gui>';
}
In2iGui::render($gui);
?>