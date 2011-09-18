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
			<tab title="Avanceret" background="light">
				<toolbar>
					<icon icon="common/time" text="Historik" name="viewHistory"/>
					<!--<divider/>
					<icon icon="common/note" title="Tilføj note" name="addNote" click="notePanel.show();noteFormula.focus()"/>
					<divider/>
					<icon icon="common/success" title="Godkend"/>-->
				</toolbar>
			</tab>
		</tabs>
	</dock>
	<boundpanel target="addNote" name="notePanel" width="200">
		<formula name="noteFormula">
			<group labels="above">
				<text label="Note:" key="word" multiline="true"/>
			</group>
			<buttons>
				<button text="Annuller" click="notePanel.hide()" small="true"/>
				<button text="Opret" highlighted="true" submit="true" small="true"/>
			</buttons>
		</formula>
	</boundpanel>
</gui>';

In2iGui::render($gui);
?>
