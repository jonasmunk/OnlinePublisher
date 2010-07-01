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

$id = Request::getString('id');

$person = Person::load($id);

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>
<update>
	<text name="personFirstname">'.In2iGui::escape($person->getFirstname()).'</text>
	<text name="personMiddlename">'.In2iGui::escape($person->getMiddlename()).'</text>
	<text name="personSurname">'.In2iGui::escape($person->getSurname()).'</text>
	<text name="personInitials">'.In2iGui::escape($person->getInitials()).'</text>
	<text name="personNickname">'.In2iGui::escape($person->getNickname()).'</text>
	<text name="personStreetname">'.In2iGui::escape($person->getStreetname()).'</text>
	<text name="personZipcode">'.In2iGui::escape($person->getZipcode()).'</text>
	<text name="personCity">'.In2iGui::escape($person->getCity()).'</text>
	<text name="personCountry">'.In2iGui::escape($person->getCountry()).'</text>
</update>
';
?>