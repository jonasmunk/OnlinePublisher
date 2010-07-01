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
$id = requestPostText('id');


$personrole = PersonRole::load($id);
$personrole->setTitle($title);
$personrole->setNote($description);
$personrole->setPersonId($personid);
$personrole->update();

setUpdateHierarchy(true);
redirect('Roles.php');
?>