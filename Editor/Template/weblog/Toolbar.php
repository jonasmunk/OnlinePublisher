<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

$gui='
<gui xmlns="uri:In2iGui" title="Dokument">
	<controller source="toolbar.js"/>
	<script>
	controller.pageId='.InternalSession::getPageId().';
	</script>
	<tabs small="true" below="true">
		<tab title="Weblog" background="light">
			<toolbar>
				<icon icon="common/close" title="Luk" name="close"/>
				<divider/>
				<icon icon="common/internet" overlay="upload" title="Udgiv" name="publish" disabled="'.(Page::isChanged(InternalSession::getPageId()) ? 'false' : 'true').'"/>
				<icon icon="common/view" title="Vis" name="preview"/>
				<icon icon="common/info" title="Info" name="properties"/>
			</toolbar>
		</tab>
		<tab title="Avanceret" background="light">
			<toolbar>
				<icon icon="common/time" title="Historik" name="history"/>
			</toolbar>
		</tab>
	</tabs>
</gui>';
In2iGui::render($gui);
?>