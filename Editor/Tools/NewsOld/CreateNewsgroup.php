<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Newsgroup.php';
require_once 'NewsController.php';

$title = requestPostText('title');
$description = requestPostText('description');

$group = new NewsGroup();
$group->setTitle($title);
$group->setNote($description);
$group->create();
$group->publish();

redirect('Group.php?id='.$group->getId());
?>