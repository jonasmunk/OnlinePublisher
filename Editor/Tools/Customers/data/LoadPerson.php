<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');
$person = Person::load($data->id);

$mails = Query::after('emailaddress')->withProperty('containingObjectId',$person->getId())->get();

$phones = Query::after('phonenumber')->withProperty('containingObjectId',$person->getId())->get();

$mailinglists = $person->getMailinglistIds();
$groups = $person->getGroupIds();

Response::sendUnicodeObject(array('person' => $person, 'emails' => $mails, 'phones' => $phones, 'mailinglists' => $mailinglists, 'groups' => $groups));
?>