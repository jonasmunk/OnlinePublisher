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
	setDocumentTextTab(requestGetText('tab'));
}
$tab = getDocumentTextTab();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">';
if ($tab=='text') {
	$gui.='<tab title="Tekst" style="Hilited"/>';
}
else {
	$gui.='<tab title="Tekst" link="Toolbar.php?tab=text"/>';
}
if ($tab=='section') {
	$gui.='<tab title="Afstande" style="Hilited"/>';
}
else {
	$gui.='<tab title="Afstande" link="Toolbar.php?tab=section"/>';
}
if ($tab=='advanced') {
	$gui.='<tab title="Avanceret" style="Hilited"/>';
}
else {
	$gui.='<tab title="Avanceret" link="Toolbar.php?tab=advanced"/>';
}
if ($tab=='image') {
	$gui.='<tab title="Billede" style="Hilited"/>';
}
else {
	$gui.='<tab title="Billede" link="Toolbar.php?tab=image"/>';
}
$gui.=
'</tabgroup>'.
'<content>';
if ($tab=='text') {
	$gui.=textTab();	
}
else if ($tab=='section') {
	$gui.=sectionTab();
}
else if ($tab=='advanced') {
	$gui.=advancedTab();
}
else if ($tab=='image') {
	$gui.=imageTab();
}
$gui.=
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","Script","Style");
writeGui($xwg_skin,$elements,$gui);

function textTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.TextForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
	'<size xmlns="uri:Style" title="Størrelse" object="FontSize" onchange="updateFontSize();"/>'.
	'<font-family xmlns="uri:Style" title="Skrift" object="FontFamily" onchange="updateForm();">'.
	fontFamilyOptions().
	'</font-family>'.
	'<text-align xmlns="uri:Style" title="Justering" object="TextAlign" onchange="updateForm();"/>'.
	'<size xmlns="uri:Style" title="Linjehøjde" object="LineHeight" onchange="updateForm();"/>'.
	'<color xmlns="uri:Style" title="Farve" object="Color" onchange="updateColor();"/>'.
	'<font-weight xmlns="uri:Style" title="Tykkelse" object="FontWeight" onchange="updateForm();"/>'.
	'<font-style xmlns="uri:Style" title="Kursiv" object="FontStyle" onchange="updateForm();"/>'.
	'<script xmlns="uri:Script">
	function updateColor() {
		var colorValue = Color.getValue();
		parent.Editor.document.forms.TextForm.color.value=colorValue;
		parent.Editor.document.forms.TextForm.text.style.color = colorValue;
	}
	function updateFontSize() {
		var fontSizeValue = FontSize.getValue();
		parent.Editor.document.forms.TextForm.text.style.fontSize = fontSizeValue;
		parent.Editor.document.forms.TextForm.fontSize.value = fontSizeValue;
	}
	function updateForm() {

		var fontFamilyValue = FontFamily.getValue();
		parent.Editor.document.forms.TextForm.fontFamily.value= fontFamilyValue;
		parent.Editor.document.forms.TextForm.text.style.fontFamily = fontFamilyValue;

		var textAlignValue = TextAlign.getValue();
		parent.Editor.document.forms.TextForm.textAlign.value= textAlignValue;
		parent.Editor.document.forms.TextForm.text.style.textAlign = textAlignValue;

		var lineHeightValue = LineHeight.getValue();
		parent.Editor.document.forms.TextForm.lineHeight.value= lineHeightValue;
		parent.Editor.document.forms.TextForm.text.style.lineHeight = lineHeightValue;

		var fontWeightValue = FontWeight.getValue();
		parent.Editor.document.forms.TextForm.fontWeight.value= fontWeightValue;
		parent.Editor.document.forms.TextForm.text.style.fontWeight = fontWeightValue;

		var fontStyleValue = FontStyle.getValue();
		parent.Editor.document.forms.TextForm.fontStyle.value= fontStyleValue;
		parent.Editor.document.forms.TextForm.text.style.fontStyle = fontStyleValue;			
	}
	function updateThis() {
		Color.setValue(parent.Editor.document.forms.TextForm.color.value);
		FontSize.setValue(parent.Editor.document.forms.TextForm.fontSize.value);
		LineHeight.setValue(parent.Editor.document.forms.TextForm.lineHeight.value);
		FontFamily.setValue(parent.Editor.document.forms.TextForm.fontFamily.value);
		
		TextAlign.setValue(parent.Editor.document.forms.TextForm.textAlign.value);
		FontWeight.setValue(parent.Editor.document.forms.TextForm.fontWeight.value);
		FontStyle.setValue(parent.Editor.document.forms.TextForm.fontStyle.value);
	}
	updateThis();
	</script>';
}

function advancedTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.TextForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
	'<size xmlns="uri:Style" title="Ord-mellemrum" object="WordSpacing" onchange="updateForm();"/>'.
	'<size xmlns="uri:Style" title="Tegn-mellemrum" object="LetterSpacing" onchange="updateForm();"/>'.
	'<size xmlns="uri:Style" title="Indrykning" object="TextIndent" onchange="updateForm();"/>'.
	'<text-transform xmlns="uri:Style" title="Bogstaver" object="TextTransform" onchange="updateForm();"/>'.
	'<font-variant xmlns="uri:Style" title="Variant" object="FontVariant" onchange="updateForm();"/>'.
	'<text-decoration xmlns="uri:Style" title="Streg" object="TextDecoration" onchange="updateForm();"/>'.
	'<script xmlns="uri:Script">
	function updateForm() {
		var wordSpacingValue = WordSpacing.getValue();
		parent.Editor.document.forms.TextForm.wordSpacing.value=wordSpacingValue;
		parent.Editor.document.forms.TextForm.text.style.wordSpacing = wordSpacingValue;
		
		var letterSpacingValue = LetterSpacing.getValue();
		parent.Editor.document.forms.TextForm.letterSpacing.value=letterSpacingValue;
		parent.Editor.document.forms.TextForm.text.style.letterSpacing = letterSpacingValue;
		
		var textIndentValue = TextIndent.getValue();
		parent.Editor.document.forms.TextForm.textIndent.value=textIndentValue;
		parent.Editor.document.forms.TextForm.text.style.textIndent = textIndentValue;

		var textTransformValue = TextTransform.getValue();
		parent.Editor.document.forms.TextForm.textTransform.value=textTransformValue;
		parent.Editor.document.forms.TextForm.text.style.textTransform = textTransformValue;

		var fontVariantValue = FontVariant.getValue();
		parent.Editor.document.forms.TextForm.fontVariant.value=fontVariantValue;
		parent.Editor.document.forms.TextForm.text.style.fontVariant = fontVariantValue;

		var textDecorationValue = TextDecoration.getValue();
		parent.Editor.document.forms.TextForm.textDecoration.value=textDecorationValue;
		parent.Editor.document.forms.TextForm.text.style.textDecoration = textDecorationValue;
	}
	function updateThis() {
		WordSpacing.setValue(parent.Editor.document.forms.TextForm.wordSpacing.value);
		LetterSpacing.setValue(parent.Editor.document.forms.TextForm.letterSpacing.value);
		TextIndent.setValue(parent.Editor.document.forms.TextForm.textIndent.value);
		TextTransform.setValue(parent.Editor.document.forms.TextForm.textTransform.value);
		FontVariant.setValue(parent.Editor.document.forms.TextForm.fontVariant.value);
		TextDecoration.setValue(parent.Editor.document.forms.TextForm.textDecoration.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function sectionTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.TextForm.submit();" target="Editor"/>'.
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
		parent.Editor.document.forms.TextForm.left.value=leftValue;
		parent.Editor.document.forms.TextForm.right.value=rightValue;
		parent.Editor.document.forms.TextForm.top.value=topValue;
		parent.Editor.document.forms.TextForm.bottom.value=bottomValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingLeft = leftValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingRight = rightValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingTop = topValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingBottom = bottomValue;			
	}
	function updateThis() {
		Left.setValue(parent.Editor.document.forms.TextForm.left.value);
		Right.setValue(parent.Editor.document.forms.TextForm.right.value);
		Top.setValue(parent.Editor.document.forms.TextForm.top.value);
		Bottom.setValue(parent.Editor.document.forms.TextForm.bottom.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function imageTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.TextForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
	'<float xmlns="uri:Style" title="Justering" object="TextAlign" onchange="updateForm();"/>'.
	'<tool title="Vælg billede" icon="Element/Image" overlay="Search" link="javascript: Chooser.open();"/>'.
	'<script xmlns="uri:Script" source="../../../Services/ImageChooser/Script.js"/>'.
	'<script xmlns="uri:Script">
	var Chooser = new ImageChooser("../../../","changeImage");
	function changeImage(id) {
		parent.Editor.document.forms.TextForm.imageId.value=id;
		Chooser.close();
	}
	function updateForm() {
		var alignValue = TextAlign.getValue();
		parent.Editor.document.forms.TextForm.imageFloat.value= alignValue;
	}
	function updateThis() {
		TextAlign.setValue(parent.Editor.document.forms.TextForm.imageFloat.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function fontFamilyOptions() {
	$output=
	'<font value="sans-serif" title="*Sans-serif*"/>'.
	'<font value="Verdana,sans-serif" title="Verdana"/>'.
	'<font value="Tahoma,Geneva,sans-serif" title="Tahoma"/>'.
	'<font value="Trebuchet MS,Helvetica,sans-serif" title="Trebuchet"/>'.
	'<font value="Geneva,Tahoma,sans-serif" title="Geneva"/>'.
	'<font value="Helvetica,sans-serif" title="Helvetica"/>'.
	'<font value="Arial,Helvetica,sans-serif" title="Arial"/>'.
	'<font value="Arial Black,Gadget,Arial,sans-serif" title="Arial Black"/>'.
	'<font value="Impact,Charcoal,Arial Black,Gadget,Arial,sans-serif" title="Impact"/>'.
	'<font value="serif" title="*Serif*"/>'.
	'<font value="Times New Roman,Times,serif" title="Times New Roman"/>'.
	'<font value="Times,Times New Roman,serif" title="Times"/>'.
	'<font value="Book Antiqua,Palatino,serif" title="Book Antiqua"/>'.
	'<font value="Palatino,Book Antiqua,serif" title="Palatino"/>'.
	'<font value="Georgia,Book Antiqua,Palatino,serif" title="Georgia"/>'.
	'<font value="Garamond,Times New Roman,Times,serif" title="Garamond"/>'.
	'<font value="cursive" title="*Kursiv*"/>'.
	'<font value="Comic Sans MS,cursive" title="Comic Sans"/>'.
	'<font value="monospace" title="*Monospace*"/>'.
	'<font value="Courier New,Courier,monospace" title="Courier New"/>'.
	'<font value="Courier,Courier New,monospace" title="Courier"/>'.
	'<font value="Lucida Console,Monaco,monospace" title="Lucida Console"/>'.
	'<font value="Monaco,Lucida Console,monospace" title="Monaco"/>'.
	'<font value="fantasy" title="*Fantasi*"/>'
	;
	return $output;
}

?>