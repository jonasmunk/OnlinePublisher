<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates
 */
require_once '../Include/Private.php';

$gui='
<gui xmlns="uri:hui" title="Dokument">
	<controller source="toolbar.js"/>
	<script>
	controller.pageId='.Request::getId().';
	</script>
	<tabs small="true" below="true">
		<tab title="'.Request::getString('title').'" background="light">
			<toolbar>
				<icon icon="common/close" title="Luk" name="close"/>
				<divider/>
				<icon icon="common/internet" overlay="upload" title="Udgiv" name="publish" disabled="'.(PageService::isChanged(Request::getId()) ? 'false' : 'true').'"/>
				<icon icon="common/view" title="Vis" name="preview"/>
				<icon icon="common/info" title="Info" name="properties"/>
			</toolbar>
		</tab>
	</tabs>
</gui>';
UI::render($gui);
?>