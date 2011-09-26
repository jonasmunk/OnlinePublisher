<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Objects/Project.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id');

$project = Project::load($id);
$parentProject = $project->getParentProjectId();
$project->remove();

InternalSession::setToolSessionVar('projects','updateHierarchy',true);

if ($parentProject>0) {
	Response::redirect('Project.php?id='.$parentProject);
} else {
	Response::redirect('Overview.php');
}
?>