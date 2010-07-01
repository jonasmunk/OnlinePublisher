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
	setDocumentHeaderTab(requestGetText('tab'));
}
$tab = getDocumentHeaderTab();

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
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.HeaderForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
	'<number xmlns="uri:Style" title="Niveau" value="1" min="1" max="6" object="Level" onchange="updateForm();"/>'.
	'<size xmlns="uri:Style" title="Størrelse" object="FontSize" onchange="updateForm();"/>'.
	'<font-family xmlns="uri:Style" title="Skrift" object="FontFamily" onchange="updateForm();">'.
	fontFamilyOptions().
	'</font-family>'.
	'<text-align xmlns="uri:Style" title="Justering" object="TextAlign" onchange="updateForm();"/>'.
	'<size xmlns="uri:Style" title="Linjehøjde" object="LineHeight" onchange="updateForm();"/>'.
	'<color xmlns="uri:Style" title="Farve" object="Color" onchange="updateForm();"/>'.
	'<font-weight xmlns="uri:Style" title="Tykkelse" object="FontWeight" onchange="updateForm();"/>'.
	'<font-style xmlns="uri:Style" title="Kursiv" object="FontStyle" onchange="updateForm();"/>'.
	'<script xmlns="uri:Script">
	function updateForm() {
		var colorValue = Color.getValue();
		parent.Editor.document.forms.HeaderForm.color.value=colorValue;
		parent.Editor.document.forms.HeaderForm.text.style.color = colorValue;

		var levelValue = Level.getValue();
		parent.Editor.document.forms.HeaderForm.level.value=levelValue;
		parent.Editor.document.forms.HeaderForm.text.className="HeaderEditor HeaderEditor"+levelValue;
		parent.Editor.document.getElementById("selectedSectionTD").className="sectionTDheader"+levelValue+" sectionSelected";

		var fontSizeValue = FontSize.getValue();
		parent.Editor.document.forms.HeaderForm.fontSize.value=fontSizeValue;
		parent.Editor.document.forms.HeaderForm.text.style.fontSize = fontSizeValue;

		var fontFamilyValue = FontFamily.getValue();
		parent.Editor.document.forms.HeaderForm.fontFamily.value= fontFamilyValue;
		parent.Editor.document.forms.HeaderForm.text.style.fontFamily = fontFamilyValue;

		var textAlignValue = TextAlign.getValue();
		parent.Editor.document.forms.HeaderForm.textAlign.value= textAlignValue;
		parent.Editor.document.forms.HeaderForm.text.style.textAlign = textAlignValue;

		var lineHeightValue = LineHeight.getValue();
		parent.Editor.document.forms.HeaderForm.lineHeight.value= lineHeightValue;
		parent.Editor.document.forms.HeaderForm.text.style.lineHeight = lineHeightValue;

		var fontWeightValue = FontWeight.getValue();
		parent.Editor.document.forms.HeaderForm.fontWeight.value= fontWeightValue;
		parent.Editor.document.forms.HeaderForm.text.style.fontWeight = fontWeightValue;

		var fontStyleValue = FontStyle.getValue();
		parent.Editor.document.forms.HeaderForm.fontStyle.value= fontStyleValue;
		parent.Editor.document.forms.HeaderForm.text.style.fontStyle = fontStyleValue;			
	}
	function updateThis() {
		Color.setValue(parent.Editor.document.forms.HeaderForm.color.value);
		Level.setValue(parent.Editor.document.forms.HeaderForm.level.value);
		FontSize.setValue(parent.Editor.document.forms.HeaderForm.fontSize.value);
		FontFamily.setValue(parent.Editor.document.forms.HeaderForm.fontFamily.value);
		TextAlign.setValue(parent.Editor.document.forms.HeaderForm.textAlign.value);
		LineHeight.setValue(parent.Editor.document.forms.HeaderForm.lineHeight.value);
		FontWeight.setValue(parent.Editor.document.forms.HeaderForm.fontWeight.value);
		FontStyle.setValue(parent.Editor.document.forms.HeaderForm.fontStyle.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function advancedTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.HeaderForm.submit();" target="Editor"/>'.
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
		valueUpdater("wordSpacing",WordSpacing.getValue());	
		valueUpdater("letterSpacing",LetterSpacing.getValue());	
		valueUpdater("textIndent",TextIndent.getValue());
		valueUpdater("textTransform",TextTransform.getValue());
		valueUpdater("fontVariant",FontVariant.getValue());
		valueUpdater("textDecoration",TextDecoration.getValue());
	}
	function updateThis() {
		WordSpacing.setValue(parent.Editor.document.forms.HeaderForm.wordSpacing.value);
		LetterSpacing.setValue(parent.Editor.document.forms.HeaderForm.letterSpacing.value);
		TextIndent.setValue(parent.Editor.document.forms.HeaderForm.textIndent.value);
		TextTransform.setValue(parent.Editor.document.forms.HeaderForm.textTransform.value);
		FontVariant.setValue(parent.Editor.document.forms.HeaderForm.fontVariant.value);
		TextDecoration.setValue(parent.Editor.document.forms.HeaderForm.textDecoration.value);
	}
	function valueUpdater(id,value) {
		var obj2 =  eval("parent.Editor.document.forms.HeaderForm."+id);
		obj2.value= value;
		eval("parent.Editor.document.forms.HeaderForm.text.style."+id+"=\'"+value+"\';");
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function sectionTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.HeaderForm.submit();" target="Editor"/>'.
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
		parent.Editor.document.forms.HeaderForm.left.value=leftValue;
		parent.Editor.document.forms.HeaderForm.right.value=rightValue;
		parent.Editor.document.forms.HeaderForm.top.value=topValue;
		parent.Editor.document.forms.HeaderForm.bottom.value=bottomValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingLeft = leftValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingRight = rightValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingTop = topValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingBottom = bottomValue;			
	}
	function updateThis() {
		Left.setValue(parent.Editor.document.forms.HeaderForm.left.value);
		Right.setValue(parent.Editor.document.forms.HeaderForm.right.value);
		Top.setValue(parent.Editor.document.forms.HeaderForm.top.value);
		Bottom.setValue(parent.Editor.document.forms.HeaderForm.bottom.value);
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