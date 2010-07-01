<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="select * from document_person, person, object where document_person.person_id = person.object_id and person.object_id = object.id AND section_id=".$id;
$person= Database::selectFirst($sql);
if ($person) {
	$output.='<personblock>'.
	'<display firstname="'.($person['show_firstname'] ? 'true' : 'false').'" middlename="'.($person['show_middlename'] ? 'true' : 'false').'" surname="'.($person['show_surname'] ? 'true' : 'false').'" initials="'.($person['show_initials'] ? 'true' : 'false').
 '" nickname="'.($person['show_nickname'] ? 'true' : 'false').'" jobtitle="'.($person['show_jobtitle'] ? 'true' : 'false').'" sex="'.($person['show_sex'] ? 'true' : 'false').'" email_job="'.($person['show_email_job'] ? 'true' : 'false').
 '" email_private="'.($person['show_email_private'] ? 'true' : 'false').'" phone_job="'.($person['show_phone_job'] ? 'true' : 'false').'" phone_private="'.($person['show_phone_private'] ? 'true' : 'false').'" streetname="'.($person['show_streetname'] ? 'true' : 'false').
 '" zipcode="'.($person['show_zipcode'] ? 'true' : 'false').'" city="'.($person['show_city'] ? 'true' : 'false').'" country="'.($person['show_country'] ? 'true' : 'false').'" webaddress="'.($person['show_webaddress'] ? 'true' : 'false').
 '" image="'.($person['show_image'] ? 'true' : 'false').'"'.
	($person['align']!='' ? ' align="'.$person['align'].'"' : '').
	'/><!--PERSON#'.$person['person_id'].'--></personblock>';
}
$dynamic=true;
?>