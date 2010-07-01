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

$id = getDocumentSection();
$pageId = getPageId();	
$show_firstname = boolToInt(requestPostText('show_firstname'));
$show_middlename = boolToInt(requestPostText('show_middlename'));
$show_surname = boolToInt(requestPostText('show_surname'));
$show_initials = boolToInt(requestPostText('show_initials'));
$show_streetname = boolToInt(requestPostText('show_streetname'));
$show_zipcode = boolToInt(requestPostText('show_zipcode'));
$show_city = boolToInt(requestPostText('show_city'));
$show_country = boolToInt(requestPostText('show_country'));
$show_nickname = boolToInt(requestPostText('show_nickname'));
$show_jobtitle = boolToInt(requestPostText('show_jobtitle'));
$show_sex = boolToInt(requestPostText('show_sex'));
$show_emailjob = boolToInt(requestPostText('show_emailjob'));
$show_emailprivate = boolToInt(requestPostText('show_emailprivate'));
$show_phonejob = boolToInt(requestPostText('show_phonejob'));
$show_phoneprivate = boolToInt(requestPostText('show_phoneprivate'));
$show_webaddress = boolToInt(requestPostText('show_webaddress'));
$show_image = boolToInt(requestPostText('show_image'));
$person_id = requestPostText('personId');
$align = requestPostText('align');
$left = requestPostText('left');
$right = requestPostText('right');
$top = requestPostText('top');
$bottom = requestPostText('bottom');


$sql="update document_person set".
" person_id=".sqlText($person_id).
" ,align=".sqlText($align).
" ,show_firstname=".sqlText($show_firstname).
" ,show_middlename=".sqlText($show_middlename).
" ,show_surname=".sqlText($show_surname).
" ,show_initials=".sqlText($show_initials).
" ,show_streetname=".sqlText($show_streetname).
" ,show_zipcode=".sqlText($show_zipcode).
" ,show_city=".sqlText($show_city).
" ,show_country=".sqlText($show_country).
" ,show_nickname=".sqlText($show_nickname).
" ,show_jobtitle=".sqlText($show_jobtitle).
" ,show_sex=".sqlText($show_sex).
" ,show_email_job=".sqlText($show_emailjob).
" ,show_email_private=".sqlText($show_emailprivate).
" ,show_phone_job=".sqlText($show_phonejob).
" ,show_phone_private=".sqlText($show_phoneprivate).
" ,show_webaddress=".sqlText($show_webaddress).
" ,show_image=".sqlText($show_image).
" where section_id=".$id;
//echo $sql;
Database::update($sql);

$sql="update document_section set".
" `left`=".sqlText($left).
",`right`=".sqlText($right).
",`top`=".sqlText($top).
",`bottom`=".sqlText($bottom).
" where id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


function boolToInt($val){
	return $val=="true" ? "1" : "0";
}

redirect('../Editor.php?section=');
?>