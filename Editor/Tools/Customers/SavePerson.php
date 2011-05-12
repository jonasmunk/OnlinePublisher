<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/Person.php';

$data = Request::getObject('data');

if (intval($data->person->id)>0) {
	$person = Person::load($data->person->id);
} else {
	$person = new Person();
}
$person->setFirstname(Request::fromUnicode($data->person->firstname));
$person->setMiddlename(Request::fromUnicode($data->person->middlename));
$person->setSurname(Request::fromUnicode($data->person->surname));
$person->setNote(Request::fromUnicode($data->person->note));
$person->setJobtitle(Request::fromUnicode($data->person->jobtitle));
$person->setNickname(Request::fromUnicode($data->person->nickname));
$person->setInitials(Request::fromUnicode($data->person->initials));
$person->setStreetname(Request::fromUnicode($data->person->streetname));
$person->setZipcode(Request::fromUnicode($data->person->zipcode));
$person->setCity(Request::fromUnicode($data->person->city));
$person->setCountry(Request::fromUnicode($data->person->country));
$person->setWebaddress(Request::fromUnicode($data->person->webaddress));
$person->setSearchable($data->person->searchable);
$person->setSex($data->person->sex);
$person->setImageId($data->person->image_id ? $data->person->image_id : 0);

$person->save();

$person->updateGroupIds($data->groups);
$person->updateMailinglistIds($data->mailinglists);
$person->updateEmailAddresses($data->emails);
$person->updatePhoneNumbers($data->phones);

$person->publish();
?>