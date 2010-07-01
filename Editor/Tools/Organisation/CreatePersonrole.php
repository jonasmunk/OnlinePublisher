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
require_once 'Functions.php';

$title = requestPostText('title');
$description = requestPostText('description');
$personid = requestPostText('personid');


$group = new PersonRole();
$group->setTitle($title);
$group->setNote($description);
$group->setPersonId($personid);
$group->create();

setUpdateHierarchy(true);
redirect('Roles.php');
?>