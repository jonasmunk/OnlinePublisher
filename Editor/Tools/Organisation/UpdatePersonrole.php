<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Personrole.php';
require_once '../../Classes/Request.php';

require_once 'Functions.php';

$title = Request::getString('title');
$description = Request::getString('description');
$personid = Request::getString('personid');
$id = Request::getString('id');


$personrole = PersonRole::load($id);
$personrole->setTitle($title);
$personrole->setNote($description);
$personrole->setPersonId($personid);
$personrole->update();

setUpdateHierarchy(true);
redirect('Roles.php');
?>