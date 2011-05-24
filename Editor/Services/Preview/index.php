<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

if (Request::exists('id')) {
	InternalSession::setPageId(Request::getInt('id'));
}
if (Request::exists('return')) {
	setPreviewReturn(Request::getString('return'));
}
$edit = Request::getBoolean('edit');

$gui='
<gui xmlns="uri:hui" title="OnlinePublisher editor">
	<controller source="controller.js"/>
	<dock url="viewer/'.($edit ? '#edit' : '').'" name="dock" position="top" frame-name="Preview">
		<tabs small="true" below="true">
			<tab title="Vis ændringer" background="light">
				<toolbar>
					<icon icon="common/close" title="Luk" name="close"/>
					<divider/>
					<icon icon="common/edit" title="Rediger" name="edit"/>
					<icon icon="common/view" title="Vis udgivet" name="view"/>
					<icon icon="common/info" title="Info" name="properties"/>
					<divider/>
					<icon icon="common/internet" overlay="upload" title="Udgiv" name="publish" disabled="true"/>
				</toolbar>
			</tab>
		</tabs>
	</dock>
</gui>';

In2iGui::render($gui);
?>
