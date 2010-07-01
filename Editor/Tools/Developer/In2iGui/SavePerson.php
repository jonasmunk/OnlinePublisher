<?php
/**
 * @package OnlinePublisher
 * @subpackage Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Person.php';
require_once '../../../Classes/Request.php';

$id = Request::getPostInt('id');
$firstname = Request::getUnicodeString('personFirstname');
$middlename = Request::getUnicodeString('personMiddlename');
$surname = Request::getUnicodeString('personSurname');

if ($id>0) {
	$person = Person::load($id);
} else {
	$person = new Person();
}
$person->setFirstname($firstname);
$person->setMiddlename($middlename);
$person->setSurname($surname);
$person->save();
$person->publish();

In2iGui::respondSuccess();
?>