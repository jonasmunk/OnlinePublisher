<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

$person = Person::load($data->personId);
if ($person) {
	$person->addGroupId($data->personGroupId);
}
?>