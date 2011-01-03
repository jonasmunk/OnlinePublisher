<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Problem.php';
require_once '../../Classes/Request.php';

$title = Request::getString('title');
$description = Request::getString('description');
$parentProject = Request::getInt('parentProject');
$milestone = Request::getInt('milestone');
$priority = Request::getFloat('priority');
$deadlineSelected = Request::getCheckbox('deadlineSelected');
$deadline = Request::getDateTime('deadline');

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
	Response::redirect('Project.php?id='.$parentProject);
} else {
	Response::redirect('Overview.php');
}
?>