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

$id = requestGetNumber('id',0);

$person = Person::load($id);
$person->publish();

redirect('PersonProperties.php?id='.$id);
?>