<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Include/XmlWebGui.php';
require_once '../Functions.php';

if (requestGetExists('tab')) {
	setDocumentImageTab(requestGetText('tab'));
}
$tab = getDocumentImageTab();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">';
if ($tab=='image') {
	$gui.='<tab title="Billede" style="Hilited"/>';
}
else {
	$gui.='<tab title="Billede" link="Toolbar.php?tab=image"/>';
}
if ($tab=='section') {
	$gui.='<tab title="Afstande" style="Hilited"/>';
}
else {
	$gui.='<tab title="Afstande" link="Toolbar.php?tab=section"/>';
}
$gui.=
'</tabgroup>'.
'<content>';
if ($tab=='image') {
	$gui.=imageTab();	
}
else if ($tab=='section') {
	$gui.=sectionTab();
}
$gui.=
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","Script","Style");
writeGui($xwg_skin,$elements,$gui);

function imageTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.ImageForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
	'<align xmlns="uri:Style" title="Justering" object="TextAlign" onchange="updateForm();"/>'.
	'<tool title="Vælg billede" icon="Element/Image" overlay="Search" link="javascript: Chooser.open();"/>'.
	'<script xmlns="uri:Script" source="../../../Services/ImageChooser/Script.js"/>'.
	'<script xmlns="uri:Script">
	var Chooser = new ImageChooser("../../../","changeImage");
	function changeImage(id) {
		parent.Editor.document.forms.ImageForm.imageId.value=id;
		parent.Editor.document.getElementById("Image").src="Image/ImageDisplayer.php?id="+id;
	}
	function updateForm() {
		var alignValue = TextAlign.getValue();
		parent.Editor.document.forms.ImageForm.align.value= alignValue;
		parent.Editor.document.getElementById("ImageDiv").setAttribute("align",alignValue);
	}
	function updateThis() {
		TextAlign.setValue(parent.Editor.document.forms.ImageForm.align.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function sectionTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.ImageForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
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
		parent.Editor.document.forms.ImageForm.left.value=leftValue;
		parent.Editor.document.forms.ImageForm.right.value=rightValue;
		parent.Editor.document.forms.ImageForm.top.value=topValue;
		parent.Editor.document.forms.ImageForm.bottom.value=bottomValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingLeft = leftValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingRight = rightValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingTop = topValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingBottom = bottomValue;
	}
	function updateThis() {
		Left.setValue(parent.Editor.document.forms.ImageForm.left.value);
		Right.setValue(parent.Editor.document.forms.ImageForm.right.value);
		Top.setValue(parent.Editor.document.forms.ImageForm.top.value);
		Bottom.setValue(parent.Editor.document.forms.ImageForm.bottom.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function imageOptions() {
	$output='<option value="" title=""/>';


	$sql="select * from image order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option value="'.$row['id'].'" title="'.encodeXML($row['title']).'"/>';
	}
	Database::free($result);
	return $output;
}

function marginOptions() {
	$output='<option value="" title=""/>';
	for ($i = 0; $i<20; $i++) {
		$output.='<option value="'.($i*5).'" title="'.($i*5).' px"/>';
	}
	return $output;
}

function alignOptions() {
	$output=
	'<option value="" title=""/>'.
	'<option value="left" title="Venstre"/>'.
	'<option value="center" title="Centreret"/>'.
	'<option value="right" title="Højre"/>'.
	'<option value="justify" title="Justeret"/>'
	;
	return $output;
}
?>