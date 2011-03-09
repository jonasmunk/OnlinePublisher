<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Person.php';
require_once '../../Classes/Objects/Emailaddress.php';
require_once '../../Classes/Objects/Phonenumber.php';

$data = Request::getObject('data');
$person=Person::load($data->id);
$person->toUnicode();

$mails = Query::after('emailaddress')->withProperty('containingObjectId',$person->getId())->get();
foreach ($mails as $mail) {
	$mail->toUnicode();
}

$phones = Query::after('phonenumber')->withProperty('containingObjectId',$person->getId())->get();
foreach ($phones as $phone) {
	$phone->toUnicode();
}

$mailinglists = $person->getMailinglistIds();
$groups = $person->getGroupIds();

In2iGui::sendObject(array('person' => $person, 'emails' => $mails, 'phones' => $phones, 'mailinglists' => $mailinglists, 'groups' => $groups));
?>