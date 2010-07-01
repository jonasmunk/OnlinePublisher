<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Filegroup.php';
require_once 'Functions.php';

$title = requestPostText('title');
$description = requestPostText('description');

$group = new FileGroup();
$group->setTitle($title);
$group->setNote($description);
$group->create();

setToolSessionVar('files','updateHierarchy',true);

redirect('Group.php?id='.$group->getId());
?>