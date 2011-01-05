<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Persongroup.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$title = Request::getString('title');
$description = Request::getString('description');

$group = new PersonGroup();
$group->setTitle($title);
$group->setNote($description);
$group->create();

setUpdateHierarchy(true);
Response::redirect('Persongroup.php?id='.$group->getId());
?>