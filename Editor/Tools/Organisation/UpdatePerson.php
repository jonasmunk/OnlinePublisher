<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Person.php';
require_once 'Functions.php';

$id = requestPostNumber('id',0);
$firstname = requestPostText('firstname');
$middlename = requestPostText('middlename');
$surname = requestPostText('surname');
$initials = requestPostText('initials');
$nickname = requestPostText('nickname');
$jobtitle = requestPostText('jobtitle');
$sex = requestPostText('sex');
$email_job = requestPostText('email_job');
$email_private = requestPostText('email_private');
$phone_job = requestPostText('phone_job');
$phone_private = requestPostText('phone_private');
$streetname = requestPostText('streetname');
$zipcode = requestPostText('zipcode');
$city = requestPostText('city');
$country = requestPostText('country');
$webaddress = requestPostText('webaddress');
$imageid = requestPostText('imageid');
$description = requestPostText('description');
$searchable = requestPostCheckbox('searchable');

$person = Person::load($id);
$person->setFirstname($firstname);
$person->setMiddlename($middlename);
$person->setSurname($surname);
$person->setInitials($initials);
$person->setNickname($nickname);
$person->setJobtitle($jobtitle);
$person->setSex($sex);
$person->setEmail_job($email_job);
$person->setEmail_private($email_private);
$person->setPhone_job($phone_job);
$person->setPhone_private($phone_private);
$person->setStreetname($streetname);
$person->setZipcode($zipcode);
$person->setCity($city);
$person->setCountry($country);
$person->setWebaddress($webaddress);
$person->setImage_id($imageid);
$person->setSearchable($searchable);
$person->update();

setUpdateHierarchy(true);

redirect('PersonProperties.php?id='.$id);
?>