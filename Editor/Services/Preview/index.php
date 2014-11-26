<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../Include/Private.php';

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
    				<more>
                        <icon icon="common/page" title="{New page;da:Ny side}" overlay="new" name="newPage"/>
                    </more>
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
	
	<boundpanel target="newPage" name="newPagePanel" width="300">
    	<formula name="newPageFormula">
    		<fields labels="above">
    			<field label="{Title; da:Titel}:">
    				<text-input key="title" value="{New page; da:Ny side}"/>
    			</field>
				<field label="{Placement; da:Placering}">
					<radiobuttons value="below" key="placement">
						<item value="below" text="{Below; da:Underpunkt}"/>
						<item value="before" text="{Before; da:Før}"/>
						<item value="after" text="{After; da:Efter}"/>
					</radiobuttons>
				</field>
    		</fields>
    		<buttons>
    			<button text="{Cancel; da:Annuller}" name="cancelNewPage" small="true"/>
    			<button text="{Create; da:Opret}" highlighted="true" submit="true" small="true"/>
    		</buttons>
    	</formula>
	</boundpanel>
</gui>';

UI::render($gui);
?>
