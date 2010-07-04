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
" person_id=".Database::text($person_id).
" ,align=".Database::text($align).
" ,show_firstname=".Database::text($show_firstname).
" ,show_middlename=".Database::text($show_middlename).
" ,show_surname=".Database::text($show_surname).
" ,show_initials=".Database::text($show_initials).
" ,show_streetname=".Database::text($show_streetname).
" ,show_zipcode=".Database::text($show_zipcode).
" ,show_city=".Database::text($show_city).
" ,show_country=".Database::text($show_country).
" ,show_nickname=".Database::text($show_nickname).
" ,show_jobtitle=".Database::text($show_jobtitle).
" ,show_sex=".Database::text($show_sex).
" ,show_email_job=".Database::text($show_emailjob).
" ,show_email_private=".Database::text($show_emailprivate).
" ,show_phone_job=".Database::text($show_phonejob).
" ,show_phone_private=".Database::text($show_phoneprivate).
" ,show_webaddress=".Database::text($show_webaddress).
" ,show_image=".Database::text($show_image).
" where section_id=".$id;
//echo $sql;
Database::update($sql);

$sql="update document_section set".
" `left`=".Database::text($left).
",`right`=".Database::text($right).
",`top`=".Database::text($top).
",`bottom`=".Database::text($bottom).
" where id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


function boolToInt($val){
	return $val=="true" ? "1" : "0";
}

redirect('../Editor.php?section=');
?>