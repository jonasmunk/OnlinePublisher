<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
	$output.='<td style="'.$sectionStyle.'" class="sectionTDperson">';
	$sql="select * from document_person, person where document_person.person_id = person.object_id AND section_id=".$sectionId;
	if ($row = Database::selectFirst($sql)) {

		/*
		$xslData='<?xml version="1.0" encoding="ISO-8859-1"?>
		<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		xmlns:p="http://uri.in2isoft.com/onlinepublisher/class/person/1.0/"
		xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
		xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/">
		<xsl:output method="html" indent="no" encoding="ISO-8859-1"/>
		<xsl:template match="o:object"><table><tr></tr></table></xsl:template>
		</xsl:stylesheet>
		';
		$output.=transformXml('<?xml version="1.0" encoding="ISO-8859-1"?>'.$row['data'],$xslData);
		*/
		$output.='<table align="'.$row['align'].'"><TR>';
		if($row['image_id']>0 && $row['show_image']){
			$sql="select filename from image where object_id =".$row['image_id'];
			if ($imagerow = Database::selectFirst($sql)) {
				$output.='<td valign="top"><img class="Person" src="../../../images/128/'.$imagerow['filename'].'.png"/></td>';
			}
		}
		$output.='<td valign="top">';
		$name = buildPersonName($row);
		if ($name!='') {
			$output.='<div class="PersonProperty PersonName">'.$name.'</div>';
		}
		if($row['show_jobtitle'] & $row['jobtitle']!='') {
			$output.='<div class="PersonProperty">'.$row['jobtitle'].'</div>';
		}
		if($row['show_sex']) {
			$output.='<div class="PersonProperty"><span class="PersonLabel">Køn:</span> '.($row['sex'] ? 'Mand' : 'Kvinde').'</div>';
		}
		$address = buildPersonAddress($row);
		if ($address!='') {
			$output.='<div class="PersonProperty PersonAddress">'.$address.'</div>';
		}
		if($row['show_phone_private'] && $row['phone_private']!='') {
			$output.='<div class="PersonProperty"><span class="PersonLabel">Tlf.:</span> '.$row['phone_private'].' <span class="PersonExtra"> (privat)</span></div>';
		}
		if($row['show_phone_job'] && $row['phone_job']!='') {
			$output.='<div class="PersonProperty"><span class="PersonLabel">Tlf.:</span> '.$row['phone_job'].' <span class="PersonExtra"> (arbejde)</span></div>';
		}
		if($row['show_email_private'] && $row['email_private']!='') {
			$output.='<div class="PersonProperty"><span class="PersonLabel">E-mail:</span> <a href="#">'.$row['email_private'].'</a> <span class="PersonExtra"> (privat)</span></div>';
		}
		if($row['show_email_job'] && $row['email_job']!='') {
			$output.='<div class="PersonProperty"><span class="PersonLabel">E-mail:</span> <a href="#">'.$row['email_job'].'</a> <span class="PersonExtra"> (arbejde)</span></div>';
		}
		if($row['show_webaddress'] && $row['webaddress']!='') {
			$output.='<div class="PersonProperty"><span class="PersonLabel">Web:</span> <a href="#">'.$row['webaddress'].'</a></div>';
		}
		$output.= '</td></tr></table>';
	}
	else{
		$output.='<img src="Graphics/PersonNotFound.gif" border="0"/>';
	}
	$output.='</td>';
	

function buildPersonName($row) {
	$fullname = '';
	$extra = '';
	if($row['show_firstname']){
		$fullname=appendWordToString($fullname,$row['firstname'],' ');
	}
	if($row['show_middlename']) {
		$fullname=appendWordToString($fullname,$row['middlename'],' ');
	}
	if($row['show_surname']){
		$fullname=appendWordToString($fullname,$row['surname'],' ');
	}
	if($row['show_initials']) {
		$extra=appendWordToString($extra,$row['initials'],' ');
	}
	if($row['show_nickname']) {
		$extra=appendWordToString($extra,$row['nickname'],'/');
	}
	if (strlen($fullname)>0 && strlen($extra)>0) {
		return $fullname.' ('.$extra.')';
	}
	elseif (strlen($fullname)>0 && strlen($extra)==0) {
		return $fullname;
	}
	else {
		return $extra;
	}
}



function buildPersonAddress($row) {
	$first = '';
	if($row['show_streetname']) {
		$first=$row['streetname'];
	}
	$second = '';
	if ($row['show_zipcode']) {
		$second = appendWordToString($second,$row['zipcode'],'');
	}
	if ($row['show_city']) {
		$second = appendWordToString($second,$row['city'],' ');
	}
	if ($row['show_country']) {
		$second = appendWordToString($second,$row['country'],' ');
	}
	return appendWordToString($first,$second,'<br/>');
}
?>