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
	setDocumentNewsTab(requestGetText('tab'));
}
$tab = getDocumentNewsTab();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">';
if ($tab=='news') {
	$gui.='<tab title="Nyheder" style="Hilited"/>';
}
else {
	$gui.='<tab title="Nyheder" link="Toolbar.php?tab=news"/>';
}
if ($tab=='section') {
	$gui.='<tab title="Afstande" style="Hilited"/>';
}
else {
	$gui.='<tab title="Afstande" link="Toolbar.php?tab=section"/>';
}
if ($tab=='view') {
	$gui.='<tab title="Visning" style="Hilited"/>';
}
else {
	$gui.='<tab title="Visning" link="Toolbar.php?tab=view"/>';
}
$gui.=
'</tabgroup>'.
'<content>';
if ($tab=='news') {
	$gui.=newsTab();	
}
else if ($tab=='section') {
	$gui.=sectionTab();
}
else if ($tab=='view') {
	$gui.=viewTab();
}
$gui.=
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","Script","Style","BarForm","DockForm");
writeGui($xwg_skin,$elements,$gui);

function browserIsGecko() {
	$agent=strtolower($_SERVER['HTTP_USER_AGENT']);
	$pos = strpos($agent, 'gecko');
	if ($pos === false) {
		return false;
	} else {
		return true;
	}
}

function newsTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.NewsForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
	'<align xmlns="uri:Style" title="Justering" object="TextAlign" onchange="updateForm();"/>'.
	'<group xmlns="uri:BarForm">'.
	'<top>'.
	'<badge>Titel:</badge>'.
	'<textfield name="title" object="Title" onblur="updateForm();"/>'.
	'<radiobutton name="type" object="ModeSingle" onclick="updateForm()"/>'.
	'<badge>Nyheder</badge>'.
	'<select name="news" object="News" onchange="updateForm();" onfocus="ModeSingle.setSelected(true);updateForm();">'.
	'<option title="Vælg nyhed..." value="0"/>'.
	buildNews().
	'</select>'.
	'<radiobutton name="type" object="ModeGroups" onclick="updateForm()"/>'.
	'<badge>Grupper:</badge>'.
	'<select name="groups" multiline="true" multiple="true" object="Groups" onchange="updateForm();" onfocus="ModeGroups.setSelected(true);updateForm();">'.
	buildGroups().
	'</select>'.
	'</top>'.
	'<bottom>'.
	'<space/><space/>'.
	'</bottom>'.
	'</group>'.
	'<script xmlns="uri:Script">
	function updateForm() {
		var alignValue = TextAlign.getValue();
		parent.Editor.document.forms.NewsForm.align.value= alignValue;
		parent.Editor.document.getElementById("NewsDiv").setAttribute("align",alignValue);
		parent.Editor.document.forms.NewsForm.title.value=Title.getValue();
		if (ModeGroups.isSelected()) {
			parent.Editor.document.forms.NewsForm.mode.value="groups";
		}
		else {
			parent.Editor.document.forms.NewsForm.mode.value="single";
		}
		parent.Editor.document.forms.NewsForm.groups.value=Groups.getValues();
		parent.Editor.document.forms.NewsForm.news.value=News.getValue();'.
		(browserIsGecko() 
		? 'parent.Editor.updatePreview()'
		: 'parent.Editor.updatePreview("News/")'
		).'
	}
	function updateThis() {
		TextAlign.setValue(parent.Editor.document.forms.NewsForm.align.value);
		Title.setValue(parent.Editor.document.forms.NewsForm.title.value);
		if (parent.Editor.document.forms.NewsForm.mode.value=="groups") {
			ModeGroups.setSelected(true);
		}
		else {
			ModeSingle.setSelected(true);
		}
		News.setValue(parent.Editor.document.forms.NewsForm.news.value);
		Groups.setValues(parent.Editor.document.forms.NewsForm.groups.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function viewTab() {
	$sectionId = getDocumentSection();
	$gui=
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.NewsForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
	'<group xmlns="uri:BarForm">'.
	'<top>'.
	'<badge>Sorter efter:</badge>'.
	'<select name="sortby" onchange="updateForm();" object="SortBy">'.
	'<option title="Startdato" value="startdate"/>'.
	'<option title="Slutdato" value="enddate"/>'.
	'<option title="Titel" value="title"/>'.
	'</select>'.
	'<badge>Max antal:</badge>'.
	'<select name="maxitems" onchange="updateForm();" object="MaxItems">'.
	'<option title="Uendeligt" value="0"/>';
	for ($i=1;$i<=50;$i++) {
		$gui.='<option title="'.$i.'" value="'.$i.'"/>';
	}
	$gui.=
	'</select>'.
	'<badge>Tid:</badge>'.
	'<select name="timetype" onchange="updateForm()" object="TimeType">'.
	'<option title="Altid" value="always"/>'.
	'<option title="Lige nu" value="now"/>'.
	'<option title="Seneste timer..." value="hours"/>'.
	'<option title="Seneste dage..." value="days"/>'.
	'<option title="Seneste uger..." value="weeks"/>'.
	'<option title="Seneste måneder..." value="months"/>'.
	'<option title="Seneste år..." value="years"/>'.
	'</select>'.
	'</top>'.
	'<bottom>'.
	'<badge>Retning:</badge>'.
	'<select name="sortdir" onchange="updateForm();" object="SortDir">'.
	'<option title="Stigende" value="ascending"/>'.
	'<option title="Faldende" value="descending"/>'.
	'</select>'.
	'<space/>'.
	'<space/>'.
	'<badge>Antal:</badge>'.
	'<select name="timecount" onchange="updateForm();" object="TimeCount">';
	for ($i=1;$i<=50;$i++) {
		$gui.='<option title="'.$i.'" value="'.$i.'"/>';
	}
	$gui.=
	'</select>'.
	'</bottom>'.
	'</group>'.
	'<script xmlns="uri:Script">
	function updateForm() {
		parent.Editor.document.forms.NewsForm.sortby.value=SortBy.getValue();
		parent.Editor.document.forms.NewsForm.maxitems.value=MaxItems.getValue();
		parent.Editor.document.forms.NewsForm.timetype.value=TimeType.getValue();
		parent.Editor.document.forms.NewsForm.sortdir.value=SortDir.getValue();
		parent.Editor.document.forms.NewsForm.timecount.value=TimeCount.getValue();'.
		(browserIsGecko() 
		? 'parent.Editor.updatePreview()'
		: 'parent.Editor.updatePreview("News/")'
		).'
	}
	function updateThis() {
		SortBy.setValue(parent.Editor.document.forms.NewsForm.sortby.value);
		MaxItems.setValue(parent.Editor.document.forms.NewsForm.maxitems.value);
		TimeType.setValue(parent.Editor.document.forms.NewsForm.timetype.value);
		SortDir.setValue(parent.Editor.document.forms.NewsForm.sortdir.value);
		TimeCount.setValue(parent.Editor.document.forms.NewsForm.timecount.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
	return $gui;
}

function sectionTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.NewsForm.submit();" target="Editor"/>'.
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
		parent.Editor.document.forms.NewsForm.left.value=leftValue;
		parent.Editor.document.forms.NewsForm.right.value=rightValue;
		parent.Editor.document.forms.NewsForm.top.value=topValue;
		parent.Editor.document.forms.NewsForm.bottom.value=bottomValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingLeft = leftValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingRight = rightValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingTop = topValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingBottom = bottomValue;
	}
	function updateThis() {
		Left.setValue(parent.Editor.document.forms.NewsForm.left.value);
		Right.setValue(parent.Editor.document.forms.NewsForm.right.value);
		Top.setValue(parent.Editor.document.forms.NewsForm.top.value);
		Bottom.setValue(parent.Editor.document.forms.NewsForm.bottom.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function buildNews() {
	$output='';
	$sql="SELECT * from object where type='news' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option value="'.encodeXML($row['id']).'" title="'.encodeXML($row['title']).'"/>';
	}
	Database::free($result);
	return $output;
}

function buildGroups() {
	$output='';
	$sql="SELECT * from object where type='newsgroup' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option value="'.encodeXML($row['id']).'" title="'.encodeXML($row['title']).'"/>';
	}
	Database::free($result);
	return $output;
}
?>