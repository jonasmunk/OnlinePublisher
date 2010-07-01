<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Newsgroup.php';
require_once 'NewsController.php';

$id = requestPostNumber('id');
$title = requestPostText('title');
$description = requestPostText('description');

$group = NewsGroup::load($id);
$group->setTitle($title);
$group->setNote($description);
$group->update();
$group->publish();

NewsController::setUpdateHierarchy(true);

redirect('Group.php?id='.$id);
?>