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
			<tab title="{View changes; da:Vis ændringer}" background="light">
				<toolbar>
					<icon icon="common/close" title="{Close; da:Luk}" name="close"/>
					<divider/>
					<icon icon="common/edit" title="{Edit; da:Rediger}" name="edit"/>
					<icon icon="common/view" title="{View published; da:Vis udgivet}" name="view"/>
					<icon icon="common/info" title="Info" name="properties"/>
					<divider/>
					<icon icon="common/internet" overlay="upload" title="{Publish; da:Udgiv}" name="publish" disabled="true"/>
				</toolbar>
			</tab>
			<tab title="{Advanced; da:Avanceret}" background="light">
				<toolbar>
					<icon icon="common/time" text="{History; da:Historik}" name="viewHistory"/>
					<divider/>
					<icon icon="inset/stamp" title="{Revise; da:Revidér}" name="review"/>
					<icon icon="common/note" title="{New note; da:Ny note}" name="addNote" overlay="new_monochrome"/>
					<divider/>
					<icon icon="common/settings" title="Design" name="design"/>
				</toolbar>
			</tab>
		</tabs>
	</dock>
	
	<boundpanel target="addNote" name="notePanel" width="200">
		<formula name="noteFormula">
			<fields labels="above">
				<field label="Note:">
					<text-input key="text" multiline="true"/>
				</field>
				<field label="Type">
					<radiobuttons value="improvement" key="kind">
						<item value="improvement" text="{Improvement; da:Forbedring}"/>
						<item value="error" text="{Error; da:Fejl}"/>
					</radiobuttons>
				</field>
			</fields>
			<buttons>
				<button text="{Cancel; da:Annuller}" name="cancelNote" small="true"/>
				<button text="{Create; da:Opret}" highlighted="true" submit="true" small="true"/>
			</buttons>
		</formula>
	</boundpanel>
	
	<boundpanel target="review" name="reviewPanel" width="300">
		<buttons align="center" bottom="10">
			<button text="{Cancel; da:Annuller}" click="reviewPanel.hide()"/>
			<button text="{Reject; da:Afvis}" name="reviewReject"/>
			<button text="{Accept; da:Godkend}" highlighted="true" name="reviewAccept"/>
		</buttons>
		<list name="reviewList"/>
	</boundpanel>
</gui>';

In2iGui::render($gui);
?>
