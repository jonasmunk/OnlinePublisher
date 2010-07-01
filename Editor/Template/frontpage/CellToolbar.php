<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';


$tab = getRequestTemplateSessionVar('frontpage','cell.toolbar.tab','tab','');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">';
if ($tab=='') {
	$gui.='<tab title="Celle" style="Hilited"/>';
}
else {
	$gui.='<tab title="Celle" link="CellToolbar.php?tab="/>';
}
/*
if ($tab=='section') {
	$gui.='<tab title="Afstande" style="Hilited"/>';
}
else {
	$gui.='<tab title="Afstande" link="CellToolbar.php?tab=section"/>';
}
*/
$gui.=
'</tabgroup>'.
'<content>';
if ($tab=='') {
	$gui.=tab();	
}
else if ($tab=='section') {
	$gui.=tab();
}
$gui.=
'<script xmlns="uri:Script">
updateToolbar();
function updateToolbar() {
	var formula = parent.Frame.EditorFrame.getDocument().forms.CellFormula;
	Rows.setValue(formula.rows.value);
	Columns.setValue(formula.columns.value);
	Title.setValue(formula.title.value);
	Type.setValue(formula.type.value);
	Width.setValue(formula.width.value);
	Height.setValue(formula.height.value);
}

function updateForm() {
	var formula = parent.Frame.EditorFrame.getDocument().forms.CellFormula;
	var td = parent.Frame.EditorFrame.getDocument().getElementById("selectedCellTD");
	var rows = Rows.getValue();
	td.setAttribute("rowspan",rows);
	formula.rows.value=rows;
	var cols = Columns.getValue();
	td.setAttribute("colspan",cols);
	formula.columns.value=cols;
	formula.type.value=Type.getValue();
	formula.title.value=Title.getValue();
	var width = Width.getValue();
	formula.width.value=width;
	td.setAttribute("width",width);
	var height = Height.getValue();
	formula.height.value=height;
	td.setAttribute("height",height);
}

function submitForm() {
	var formula = parent.Frame.EditorFrame.getDocument().forms.CellFormula;
	formula.submit();
}

function deleteCell() {
	var formula = parent.Frame.EditorFrame.getDocument().forms.CellDeleteFormula;
	formula.submit();
}
</script>'.
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","Script","Style","BarForm");
writeGui($xwg_skin,$elements,$gui);

function tab() {
	$gui = 
	'<tool title="Annuller" icon="Basic/Stop" link="Editor.php?selectedCell=0" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: submitForm();"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="javascript: deleteCell();"/>'.
	'<divider/>'.
	'<group xmlns="uri:BarForm">'.
	'<top>'.
	'<badge>Titel:</badge>'.
	'<textfield name="title" object="Title" onblur="updateForm();"/>'.
	'<badge>Bredde:</badge>'.
	'<select name="width" object="Width" onchange="updateForm();">'.
	'<option title="" value=""/>';
	for ($i=1;$i<=20;$i++) {
		$gui.='<option title="'.($i*5).' %" value="'.($i*5).'%"/>';
	}
	$gui.=
	'</select>'.
	'</top>'.
	'<bottom>'.
	'<badge>Variant:</badge>'.
	'<select name="type" object="Type" onblur="updateForm();">'.
	'<option title="" value=""/>'.
	'<option title="Boks" value="box"/>'.
	'</select>'.
	'<badge>Højde:</badge>'.
	'<select name="height" object="Height" onchange="updateForm();">'.
	'<option title="" value=""/>';
	for ($i=1;$i<=50;$i++) {
		$gui.='<option title="'.($i*10).' px" value="'.($i*10).'"/>';
	}
	$gui.=
	'</select>'.
	'</bottom>'.
	'</group>'.
	'<divider/>'.
	'<number xmlns="uri:Style" object="Columns" title="Kolonner" min="1" onchange="updateForm()"/>'.
	'<number xmlns="uri:Style" object="Rows" title="Rækker" min="1" onchange="updateForm()"/>';
	return $gui;
}
?>