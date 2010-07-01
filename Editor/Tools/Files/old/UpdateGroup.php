<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Filegroup.php';
require_once 'Functions.php';

$id = requestPostNumber('id',0);
$title = requestPostText('title');
$description = requestPostText('description');

$group = FileGroup::load($id);
$group->setTitle($title);
$group->setNote($description);
$group->update();

setToolSessionVar('files','updateHierarchy',true);

redirect('Group.php?id='.$id);
?>