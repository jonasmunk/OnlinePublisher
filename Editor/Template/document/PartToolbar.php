<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Part.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';

// Get session variables
$partId = getPartContextSessionVar('part.id');
$partType = getPartContextSessionVar('part.type');
$formPath = getPartContextSessionVar('form.path');

$sectionId = getRequestTemplateSessionVar('document','selectedSection','section',0);

$part = Part::load($partType,$partId);

if ($part->isIn2iGuiEnabled()) {
	$gui='
	<gui xmlns="uri:In2iGui" title="Toolbar">
		<controller source="js/PartToolbar.js"/>
		<controller source="../../Parts/'.$partType.'/toolbar.js"/>
		<script>
		partToolbar.pageId='.InternalSession::getPageId().';
		partToolbar.sectionId='.$sectionId.';
		partToolbar.partId='.$partId.';
		</script>
		<tabs small="true" below="true">
			<tab title="Part" background="light">
				<toolbar>
					<icon icon="common/stop" title="Annuller" click="partToolbar.cancel()"/>
					<icon icon="common/save" title="Gem" click="partToolbar.save()"/>
					<icon icon="common/delete" title="Slet" click="partToolbar.deletePart()"/>
					'.$part->getMainToolbarBody().'
				</toolbar>
			</tab>
			<tab title="Afstande" background="light">
				<toolbar>
					<icon icon="common/stop" title="Annuller" click="partToolbar.cancel()"/>
					<icon icon="common/save" title="Gem" click="partToolbar.save()"/>
					<icon icon="common/delete" title="Slet" click="partToolbar.deletePart()"/>
					<divider/>
					<style-length label="Venstre" name="marginLeft"/>
					<style-length label="H&#248;jre" name="marginRight"/>
					<style-length label="Top" name="marginTop"/>
					<style-length label="Bund" name="marginBottom"/>
					<divider/>
					<style-length label="Bredde" name="sectionWidth"/>
					<segmented label="Tekstoml&#248;b" name="sectionFloat" allow-null="true">
						<item icon="style/float_none" value=""/>
						<item icon="style/float_left" value="left"/>
						<item icon="style/float_right" value="right"/>
					</segmented>
				</toolbar>
			</tab>';
			foreach ($part->getToolbars() as $title => $body) {
				$gui.='<tab title="'.$title.'" background="light">
				<toolbar>
					<icon icon="common/stop" title="Annuller" click="partToolbar.cancel()"/>
					<icon icon="common/save" title="Gem" click="partToolbar.save()"/>
					<icon icon="common/delete" title="Slet" click="partToolbar.deletePart()"/>
					<divider/>'.$body.
				'</toolbar>
				</tab>';
			}
			$gui.='
		</tabs>
	</gui>';
	In2iGui::render($gui);
	exit;
}

$selectedTab = $part->getToolbarDefaultTab();
if (requestGetExists('tab')) {
	$selectedTab = requestGetText('tab');
}


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup>';
$tabs = $part -> getToolbarTabs();
foreach ($tabs as $tab => $info) {
	$gui.='<tab title="'.encodeXML($info['title']).'" link="PartToolbar.php?tab='.$tab.'"'.
	($selectedTab==$tab ? ' style="Hilited"' : '').
	'/>';
}
$gui.=
'<tab title="Afstande"'.
($selectedTab=='distance' ? ' style="Hilited"' : ' link="PartToolbar.php?tab=distance"').
'/>'.
'<tab title="Tekstomløb"'.
($selectedTab=='float' ? ' style="Hilited"' : ' link="PartToolbar.php?tab=float"').
'/>'.
'</tabgroup>'.
'<content>'.
'<script xmlns="uri:Script">
var editorDocument = parent.Frame.EditorFrame.getDocument();
var editorFrame = parent.Frame.Editor;
var formula = parent.Frame.EditorFrame.getDocument().forms.PartForm;
var section = parent.Frame.EditorFrame.getDocument().getElementById("selectedSectionTD");
function submit() {
	formula.submit()
}
</script>'.
'<tool title="Annuller" icon="Basic/Stop" link="Editor.php?section=" target="Editor"/>'.
'<tool title="Gem" icon="Basic/Save" link="javascript: submit();"/>'.
'<tool title="Slet" icon="Basic/Delete" link="DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
'<divider/>';
if ($selectedTab=='distance') {
	$gui.=distanceTab();
} if ($selectedTab=='float') {
	$gui.=floatTab();
} else {
	$gui.=$part->getToolbarContent($selectedTab);
}
$gui.=
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","DockForm","BarForm","Script","Style");
writeGui($xwg_skin,$elements,$gui);


function distanceTab() {
	return
	'<size xmlns="uri:Style" title="Venstre" object="Left" onchange="updateForm();"/>'.
	'<size xmlns="uri:Style" title="Højre" object="Right" onchange="updateForm();"/>'.
	'<size xmlns="uri:Style" title="Top" object="Top" onchange="updateForm();"/>'.
	'<size xmlns="uri:Style" title="Bund" object="Bottom" onchange="updateForm();"/>'.
	'<script xmlns="uri:Script">
	function updateForm() {
		var leftValue = Left.getValue();
		var rightValue = Right.getValue();
		var topValue = Top.getValue();
		var bottomValue = Bottom.getValue();
		formula.left.value=leftValue;
		formula.right.value=rightValue;
		formula.top.value=topValue;
		formula.bottom.value=bottomValue;
		section.style.paddingLeft = leftValue;
		section.style.paddingRight = rightValue;
		section.style.paddingTop = topValue;
		section.style.paddingBottom = bottomValue;			
	}
	function updateThis() {
		Left.setValue(formula.left.value);
		Right.setValue(formula.right.value);
		Top.setValue(formula.top.value);
		Bottom.setValue(formula.bottom.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function floatTab() {
	return
	'<size xmlns="uri:Style" title="Bredde" object="Width" onchange="updateForm();"/>'.
	'<align xmlns="uri:Style" title="Tekstomløb" object="Float" onchange="updateForm();"/>'.
	'<script xmlns="uri:Script">
	function updateForm() {
		formula.width.value=Width.getValue();
		formula.float.value=Float.getValue();
	}
	function updateThis() {
		Width.setValue(formula.width.value);
		Float.setValue(formula.float.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}
?>