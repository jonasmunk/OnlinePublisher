<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Person.php';
require_once '../../Classes/Request.php';

require_once 'Functions.php';
$firstname = Request::getString('firstname');
$middlename = Request::getString('middlename');
$surname = Request::getString('surname');
$initials = Request::getString('initials');
$nickname = Request::getString('nickname');
$jobtitle = Request::getString('jobtitle');
$sex = Request::getString('sex');
$email_job = Request::getString('email_job');
$email_private = Request::getString('email_private');
$phone_job = Request::getString('phone_job');
$phone_private = Request::getString('phone_private');
$streetname = Request::getString('streetname');
$zipcode = Request::getString('zipcode');
$city = Request::getString('city');
$country = Request::getString('country');
$webaddress = Request::getString('webaddress');
$imageid = Request::getString('imageid');

$person = new Person();
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
$person->create();

$personId = $person->getId();

// Add to group
$group=getPersonGroup();
if ($group>0) {
	$sql="insert into persongroup_person (person_id,persongroup_id)".
	" values (".$personId.",".$group.")";
	Database::insert($sql);
}

setUpdateHierarchy(true);

redirect('PersonProperties.php?id='.$personId);

?>