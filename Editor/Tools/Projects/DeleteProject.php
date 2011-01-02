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

$id = requestGetNumber('id');

$project = Project::load($id);
$parentProject = $project->getParentProjectId();
$project->remove();

InternalSession::setToolSessionVar('projects','updateHierarchy',true);

if ($parentProject>0) {
	redirect('Project.php?id='.$parentProject);
} else {
	redirect('Overview.php');
}
?>