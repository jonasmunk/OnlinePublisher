<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Part.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';

$partId = getPartContextSessionVar('part.id');
$partType = getPartContextSessionVar('part.type');
$sectionId = getRequestTemplateSessionVar('frontpage','selectedSection','selectedSection',0);

$part = Part::load($partType,$partId);
$selectedTab = $part->getToolbarDefaultTab();
if (requestGetExists('tab')) {
	$selectedTab = requestGetText('tab');
}

$formPath = getPartContextSessionVar('form.path');

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
'</tabgroup>'.
'<content>'.
'<script xmlns="uri:Script">
var editorDocument = parent.Frame.EditorFrame.getDocument();
var editorFrame = parent.Frame.Editor;
var formula = parent.Frame.EditorFrame.getDocument().forms.PartForm;
var section = parent.Frame.EditorFrame.getDocument().getElementById("selectedSection");
function submit() {
	formula.submit()
}
</script>'.
'<tool title="Annuller" icon="Basic/Stop" link="Editor.php?selectedSection=0" target="Editor"/>'.
'<tool title="Gem" icon="Basic/Save" link="javascript: submit();"/>'.
'<tool title="Slet" icon="Basic/Delete" link="DeleteSection.php?id='.$sectionId.'" target="Editor"/>'.
'<divider/>';
if ($selectedTab=='distance') {
	$gui.=distanceTab();
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
		formula.value=rightValue;
		formula.value=topValue;
		formula.value=bottomValue;
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
?>