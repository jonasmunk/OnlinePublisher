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
require_once '../../Classes/Project.php';

$title = requestPostText('title');
$description = requestPostText('description');
$parentProject = requestPostNumber('parentProject');

$project = new Project();
$project->setTitle($title);
$project->setNote($description);
$project->setParentProjectId($parentProject);
$project->create();
$project->publish();

setToolSessionVar('projects','updateHierarchy',true);
if ($parentProject>0) {
	redirect('Project.php?id='.$parentProject);
} else {
	redirect('Project.php?id='.$project->getId());
}
?>