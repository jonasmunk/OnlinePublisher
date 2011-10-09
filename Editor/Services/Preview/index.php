<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/Request.php';

if (Request::exists('id')) {
	InternalSession::setPageId(Request::getInt('id'));
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
					<divider/>
					<icon icon="inset/stamp" title="Revidér" name="review"/>
					<icon icon="common/note" title="Tilføj note" name="addNote" overlay="new_monochrome"/>
				</toolbar>
			</tab>
		</tabs>
	</dock>
	
	<boundpanel target="addNote" name="notePanel" width="200">
		<formula name="noteFormula">
			<group labels="above">
				<text label="Note:" key="text" multiline="true"/>
				<radiobuttons label="Type" value="improvement" key="kind">
					<item value="improvement" text="Forbedring"/>
					<item value="error" text="Fejl"/>
				</radiobuttons>
			</group>
			<buttons>
				<button text="Annuller" name="cancelNote" small="true"/>
				<button text="Opret" highlighted="true" submit="true" small="true"/>
			</buttons>
		</formula>
	</boundpanel>
	
	<boundpanel target="review" name="reviewPanel" width="300">
		<buttons align="center" bottom="10">
			<button text="Annuller" click="reviewPanel.hide()"/>
			<button text="Afvis" name="reviewReject"/>
			<button text="Godkend" highlighted="true" name="reviewAccept"/>
		</buttons>
		<list name="reviewList"/>
	</boundpanel>
</gui>';

In2iGui::render($gui);
?>
