<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Problem.php';

$title = requestPostText('title');
$description = requestPostText('description');
$parentProject = requestPostNumber('parentProject');
$milestone = requestPostNumber('milestone');
$priority = requestPostFloat('priority');
$deadlineSelected = requestPostCheckbox('deadlineSelected');
$deadline = requestPostDateTime('deadline');

$problem = new Problem();
$problem->setTitle($title);
$problem->setNote($description);
if ($deadlineSelected) {
    $problem->setDeadline($deadline);
}
$problem->setContainingObjectId($parentProject);
$problem->setMilestoneId($milestone);
$problem->setPriority($priority);
$problem->create();
$problem->publish();

if ($parentProject>0) {
	redirect('Project.php?id='.$parentProject);
} else {
	redirect('Overview.php');
}
?>