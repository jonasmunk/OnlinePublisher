<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Project.php';

$id = requestPostNumber('id');
$title = requestPostText('title');
$description = requestPostText('description');
$parentProject = requestPostNumber('parentProject');

$project = Project::load($id);
$project->setTitle($title);
$project->setNote($description);
$project->setParentProjectId($parentProject);
$project->update();
$project->publish();

InternalSession::setToolSessionVar('projects','updateHierarchy',true);

redirect('Project.php?id='.$id);
?>