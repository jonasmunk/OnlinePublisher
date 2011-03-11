<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/Person.php';
require_once '../../Classes/Objects/Persongroup.php';

$data = Request::getObject('data');

$person = Person::load($data->personId);
$person->addGroupId($data->personGroupId);
?>