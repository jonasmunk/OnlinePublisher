<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Imagegroup.php';

require_once 'ImagesController.php';

$title = requestPostText('title');
$description = requestPostText('description');

$group = new ImageGroup();
$group->setTitle($title);
$group->setNote($description);
$group->create();
$group->publish();

ImagesController::setUpdateHierarchy(true);

redirect('Group.php?id='.$group->getId());
?>