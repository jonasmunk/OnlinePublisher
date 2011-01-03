<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Problem.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');
$title = Request::getString('title');
$description = Request::getString('description');
$parentProject = Request::getInt('parentProject');
$milestone = Request::getInt('milestone');
$priority = Request::getFloat('priority');
$deadlineSelected = Request::getCheckbox('deadlineSelected');
$completed = Request::getCheckbox('completed');
$deadline = Request::getDateTime('deadline');

$problem = Problem::load($id);
$problem->setTitle($title);
$problem->setNote($description);
if ($deadlineSelected) {
    $problem->setDeadline($deadline);
} else {
    $problem->setDeadline(null);
}
$problem->setCompleted($completed);
$problem->setContainingObjectId($parentProject);
$problem->setMilestoneId($milestone);
$problem->setPriority($priority);
$problem->update();
$problem->publish();

Response::redirect(Request::getString('return'));
?>