<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Project.php';
require_once '../../Classes/Request.php';

$title = Request::getString('title');
$description = Request::getString('description');
$parentProject = Request::getInt('parentProject');

$project = new Project();
$project->setTitle($title);
$project->setNote($description);
$project->setParentProjectId($parentProject);
$project->create();
$project->publish();

InternalSession::setToolSessionVar('projects','updateHierarchy',true);
if ($parentProject>0) {
	Response::redirect('Project.php?id='.$parentProject);
} else {
	Response::redirect('Project.php?id='.$project->getId());
}
?>