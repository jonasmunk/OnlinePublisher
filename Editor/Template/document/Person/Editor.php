<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$sql="select * from document_person,document_section where document_person.section_id=document_section.id and document_section.id=".$sectionId;
	$row = Database::selectFirst($sql);
	$style='';
	
	$person_id = $row['person_id'];
	$sql="select * from person where object_id =".$row['person_id'];
	$personrow = Database::selectFirst($sql);
	
	$output.=
	'<td style="'.$sectionStyle.'" id="selectedSectionTD" class="sectionTDtext sectionSelected">'.
	'<div align="'.$row['align'].'">'.
	'<form name="PersonForm" action="Person/Update.php" method="post" style="margin: 0px; padding: 0px;">'.
	'<input type="hidden" name="align" value="'.$row['align'].'"/>'.
	'<input type="hidden" name="show_firstname" value="'.intToBool($row['show_firstname']).'"/>'.
	'<input type="hidden" name="show_middlename" value="'.intToBool($row['show_middlename']).'"/>'.
	'<input type="hidden" name="show_surname" value="'.intToBool($row['show_surname']).'"/>'.
	'<input type="hidden" name="show_initials" value="'.intToBool($row['show_initials']).'"/>'.
	'<input type="hidden" name="show_streetname" value="'.intToBool($row['show_streetname']).'"/>'.
	'<input type="hidden" name="show_zipcode" value="'.intToBool($row['show_zipcode']).'"/>'.
	'<input type="hidden" name="show_city" value="'.intToBool($row['show_city']).'"/>'.
	'<input type="hidden" name="show_country" value="'.intToBool($row['show_country']).'"/>'.
	'<input type="hidden" name="show_nickname" value="'.intToBool($row['show_nickname']).'"/>'.
	'<input type="hidden" name="show_jobtitle" value="'.intToBool($row['show_jobtitle']).'"/>'.
	'<input type="hidden" name="show_sex" value="'.intToBool($row['show_sex']).'"/>'.
	'<input type="hidden" name="show_emailjob" value="'.intToBool($row['show_email_job']).'"/>'.
	'<input type="hidden" name="show_emailprivate" value="'.intToBool($row['show_email_private']).'"/>'.
	'<input type="hidden" name="show_phonejob" value="'.intToBool($row['show_phone_job']).'"/>'.
	'<input type="hidden" name="show_phoneprivate" value="'.intToBool($row['show_phone_private']).'"/>'.
	'<input type="hidden" name="show_webaddress" value="'.intToBool($row['show_webaddress']).'"/>'.
	'<input type="hidden" name="show_image" value="'.intToBool($row['show_image']).'"/>'.
	'<input type="hidden" name="left" value="'.$row['left'].'"/>'.
	'<input type="hidden" name="right" value="'.$row['right'].'"/>'.
	'<input type="hidden" name="top" value="'.$row['top'].'"/>'.
	'<input type="hidden" name="bottom" value="'.$row['bottom'].'"/>'.
	'<input type="hidden" name="personId" value="'.$person_id.'"/>'.
	'<script>parent.Toolbar.location=\'Person/Toolbar.php?\'+Math.random();</script>'.
	'<div id="PersonPreview"></div>'.
	'</div>';
	// Build AJAX script
	$output.=
	'<script src="Person/Script.js"></script>'.
	'<script>
	xmlReq = new XmlRequest();
	function updatePreview() {
			var loc = document.location.toString();
			var prefix=loc.substr(0,loc.lastIndexOf("/")+1);
			var id = document.forms.PersonForm.personId.value;
			xmlReq.get(prefix+"Person/ScriptServer.php?id="+id+"&"+new Date().getTime(),showPerson);
	}
	updatePreview();
	function saveSection() {
		document.forms.PersonForm.submit();
	}
	</script>'.
	'</td>';

	function intToBool($val){
		return $val==1 ? "true" : "false";
	}
?>