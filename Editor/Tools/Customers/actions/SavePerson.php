<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if (intval($data->person->id)>0) {
	$person = Person::load($data->person->id);
} else {
	$person = new Person();
}
$person->setFirstname($data->person->firstname);
$person->setMiddlename($data->person->middlename);
$person->setSurname($data->person->surname);
$person->setNote($data->person->note);
$person->setJobtitle($data->person->jobtitle);
$person->setNickname($data->person->nickname);
$person->setInitials($data->person->initials);
$person->setStreetname($data->person->streetname);
$person->setZipcode($data->person->zipcode);
$person->setCity($data->person->city);
$person->setCountry($data->person->country);
$person->setWebaddress($data->person->webaddress);
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