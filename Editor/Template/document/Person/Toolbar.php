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
	setDocumentPersonTab(requestGetText('tab'));
}
$tab = getDocumentPersonTab();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">';
if ($tab=='person') {
	$gui.='<tab title="Person" style="Hilited"/>';
}
else {
	$gui.='<tab title="Person" link="Toolbar.php?tab=person"/>';
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
if ($tab=='person') {
	$gui.=personTab();	
}
else if ($tab=='section') {
	$gui.=sectionTab();
}
$gui.=
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","Script","Style","DockForm");
writeGui($xwg_skin,$elements,$gui);

function personTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.PersonForm.submit();" target="Editor"/>'.
	'<tool title="Slet" icon="Basic/Delete" link="../DeleteSection.php?section='.$sectionId.'" target="Editor"/>'.
	'<divider/>'.
	'<align xmlns="uri:Style" title="Justering" object="TextAlign" onchange="updateForm();"/>'.
	'<tool title="Vælg person" icon="Element/Person" overlay="Search" link="javascript: Chooser.open();"/>'.
	'<divider/>'.
	'<form xmlns="uri:DockForm" name="ShowPersonForm">'.
	'<row>'.
	'<cell>'.
	'<checkbox badge="Fornavn" name="show_firstname" onclick="updateForm();"/>'.
	'<checkbox badge="Mellemnavn" name="show_middlename" onclick="updateForm();"/>'.
	'</cell>'.        
	'<cell>'.         
	'<checkbox badge="Efternavn" name="show_surname" onclick="updateForm();"/>'.
	'<checkbox badge="Initialer" name="show_initials" onclick="updateForm();"/>'.
	'</cell>'.        
	'<cell>'.	      
	'<checkbox badge="Gade" name="show_streetname" onclick="updateForm();"/>'.
	'<checkbox badge="Postnr." name="show_zipcode" onclick="updateForm();"/>'.
	'</cell>'.        
	'<cell>'.	      
	'<checkbox badge="By" name="show_city" onclick="updateForm();"/>'.
	'<checkbox badge="Land" name="show_country" onclick="updateForm();"/>'.
	'</cell>'.        
	'<cell>'.         
	'<checkbox badge="Kaldenavn" name="show_nickname" onclick="updateForm();"/>'.
	'<checkbox badge="Beskæftigelse" name="show_jobtitle" onclick="updateForm();"/>'.
	'</cell>'.	      
	'<cell>'.         
	'<checkbox badge="Køn" name="show_sex" onclick="updateForm();"/>'.
	'<checkbox badge="Email på job" name="show_email_job" onclick="updateForm();"/>'.
	'</cell>'.        
	'<cell>'.         
	'<checkbox badge="Email privat" name="show_email_private" onclick="updateForm();"/>'.
	'<checkbox badge="Tlf. på job" name="show_phone_job" onclick="updateForm();"/>'.
	'</cell>'.        
	'<cell>'.         
	'<checkbox badge="Tlf. privat" name="show_phone_private" onclick="updateForm();"/>'.
	'<checkbox badge="Webadresse" name="show_webaddress" onclick="updateForm();"/>'.
	'</cell>'.        
	'<cell>'.         
	'<checkbox badge="Billede" name="show_image" onclick="updateForm();"/>'.
	'</cell>'.
	'</row>'.
	'</form>'.
	'<script xmlns="uri:Script" source="../../../Services/PersonChooser/Script.js"/>'.
	'<script xmlns="uri:Script">
	var Chooser = new PersonChooser("../../../","changePerson");
	function changePerson(id) {
		parent.Editor.document.forms.PersonForm.personId.value=id;
		window.setTimeout("parent.Editor.updatePreview()");
	}
	
	function updateForm() {
		var alignValue = TextAlign.getValue();		
		var firstnameValue = document.forms.ShowPersonForm.show_firstname.checked;
		var middlenameValue = document.forms.ShowPersonForm.show_middlename.checked;
		var surnameValue = document.forms.ShowPersonForm.show_surname.checked;
		var initialsValue = document.forms.ShowPersonForm.show_initials.checked;
		var streetnameValue = document.forms.ShowPersonForm.show_streetname.checked;
		var zipcodeValue = document.forms.ShowPersonForm.show_zipcode.checked;
		var cityValue =document.forms.ShowPersonForm.show_city.checked;
		var countryValue = document.forms.ShowPersonForm.show_country.checked;
		var nicknameValue = document.forms.ShowPersonForm.show_nickname.checked;
		var jobtitleValue = document.forms.ShowPersonForm.show_jobtitle.checked;
		var sexValue = document.forms.ShowPersonForm.show_sex.checked;
		var emailjobValue = document.forms.ShowPersonForm.show_email_job.checked;
		var emailprivateValue = document.forms.ShowPersonForm.show_email_private.checked;
		var phonejobValue = document.forms.ShowPersonForm.show_phone_job.checked;
		var phoneprivateValue = document.forms.ShowPersonForm.show_phone_private.checked;
		var webaddressValue = document.forms.ShowPersonForm.show_webaddress.checked;
		var imageValue = document.forms.ShowPersonForm.show_image.checked;
		parent.Editor.document.forms.PersonForm.align.value= alignValue;
		parent.Editor.document.forms.PersonForm.show_firstname.value= firstnameValue;
		parent.Editor.document.forms.PersonForm.show_middlename.value= middlenameValue;
		parent.Editor.document.forms.PersonForm.show_surname.value= surnameValue;
		parent.Editor.document.forms.PersonForm.show_initials.value= initialsValue;
		parent.Editor.document.forms.PersonForm.show_streetname.value= streetnameValue;
		parent.Editor.document.forms.PersonForm.show_zipcode.value= zipcodeValue;
		parent.Editor.document.forms.PersonForm.show_city.value= cityValue;
		parent.Editor.document.forms.PersonForm.show_country.value= countryValue;
		parent.Editor.document.forms.PersonForm.show_nickname.value= nicknameValue;
		parent.Editor.document.forms.PersonForm.show_jobtitle.value= jobtitleValue;
		parent.Editor.document.forms.PersonForm.show_sex.value= sexValue;
		parent.Editor.document.forms.PersonForm.show_emailjob.value= emailjobValue;
		parent.Editor.document.forms.PersonForm.show_emailprivate.value= emailprivateValue;
		parent.Editor.document.forms.PersonForm.show_phonejob.value= phonejobValue;
		parent.Editor.document.forms.PersonForm.show_phoneprivate.value= phoneprivateValue;
		parent.Editor.document.forms.PersonForm.show_webaddress.value= webaddressValue;
		parent.Editor.document.forms.PersonForm.show_image.value= imageValue;
		//parent.Editor.document.getElementById("PersonDiv").setAttribute("align",alignValue);
		parent.Editor.updatePreview();
	}
	
	function updateThis() {
		TextAlign.setValue(parent.Editor.document.forms.PersonForm.align.value);
		document.forms.ShowPersonForm.show_firstname.checked = (parent.Editor.document.forms.PersonForm.show_firstname.value == "true");
		document.forms.ShowPersonForm.show_middlename.checked=(parent.Editor.document.forms.PersonForm.show_middlename.value == "true");
		document.forms.ShowPersonForm.show_surname.checked=(parent.Editor.document.forms.PersonForm.show_surname.value == "true");
		document.forms.ShowPersonForm.show_initials.checked=(parent.Editor.document.forms.PersonForm.show_initials.value == "true");
		document.forms.ShowPersonForm.show_streetname.checked=(parent.Editor.document.forms.PersonForm.show_streetname.value == "true");
		document.forms.ShowPersonForm.show_zipcode.checked=(parent.Editor.document.forms.PersonForm.show_zipcode.value == "true");
		document.forms.ShowPersonForm.show_city.checked=(parent.Editor.document.forms.PersonForm.show_city.value == "true");
		document.forms.ShowPersonForm.show_country.checked=(parent.Editor.document.forms.PersonForm.show_country.value == "true");
		document.forms.ShowPersonForm.show_nickname.checked=(parent.Editor.document.forms.PersonForm.show_nickname.value == "true");
		document.forms.ShowPersonForm.show_jobtitle.checked=(parent.Editor.document.forms.PersonForm.show_jobtitle.value == "true");
		document.forms.ShowPersonForm.show_sex.checked=(parent.Editor.document.forms.PersonForm.show_sex.value == "true");
		document.forms.ShowPersonForm.show_email_job.checked=(parent.Editor.document.forms.PersonForm.show_emailjob.value == "true");
		document.forms.ShowPersonForm.show_email_private.checked=(parent.Editor.document.forms.PersonForm.show_emailprivate.value == "true");
		document.forms.ShowPersonForm.show_phone_job.checked=(parent.Editor.document.forms.PersonForm.show_phonejob.value == "true");
		document.forms.ShowPersonForm.show_phone_private.checked=(parent.Editor.document.forms.PersonForm.show_phoneprivate.value == "true");
		document.forms.ShowPersonForm.show_webaddress.checked=(parent.Editor.document.forms.PersonForm.show_webaddress.value == "true");
		document.forms.ShowPersonForm.show_image.checked=(parent.Editor.document.forms.PersonForm.show_image.value == "true");
	}
	updateThis();
	</script>'.
	'<flexible/>';
}

function sectionTab() {
	$sectionId = getDocumentSection();
	return
	'<tool title="Annuller" icon="Basic/Stop" link="../Editor.php?section=" target="Editor"/>'.
	'<tool title="Gem" icon="Basic/Save" link="javascript: parent.Editor.document.forms.PersonForm.submit();" target="Editor"/>'.
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
		parent.Editor.document.forms.PersonForm.left.value=leftValue;
		parent.Editor.document.forms.PersonForm.right.value=rightValue;
		parent.Editor.document.forms.PersonForm.top.value=topValue;
		parent.Editor.document.forms.PersonForm.bottom.value=bottomValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingLeft = leftValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingRight = rightValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingTop = topValue;
		parent.Editor.document.getElementById("selectedSectionTD").style.paddingBottom = bottomValue;
	}
	function updateThis() {
		Left.setValue(parent.Editor.document.forms.PersonForm.left.value);
		Right.setValue(parent.Editor.document.forms.PersonForm.right.value);
		Top.setValue(parent.Editor.document.forms.PersonForm.top.value);
		Bottom.setValue(parent.Editor.document.forms.PersonForm.bottom.value);
	}
	updateThis();
	</script>'.
	'<flexible/>';
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