<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Person.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = Request::getInt('id',0);

$person = Person::load($id);
$person->remove();

setUpdateHierarchy(true);
$group=getPersonGroup();
if ($group>0) {
	Response::redirect('Persongroup.php');
}
else {
	Response::redirect('Library.php');
}
?>