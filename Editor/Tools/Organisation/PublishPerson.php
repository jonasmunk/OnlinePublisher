<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Person.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);

$person = Person::load($id);
$person->publish();

Response::redirect('PersonProperties.php?id='.$id);
?>