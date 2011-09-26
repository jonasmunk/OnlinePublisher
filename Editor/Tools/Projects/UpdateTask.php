<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Objects/Task.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id');
$title = Request::getString('title');
$description = Request::getString('description');
$parentProject = Request::getInt('parentProject');
$milestone = Request::getInt('milestone');
$priority = Request::getFloat('priority');
$deadlineSelected = Request::getCheckbox('deadlineSelected');
$completed = Request::getCheckbox('completed');
$deadline = Request::getDateTime('deadline');

$task = Task::load($id);
$task->setTitle($title);
$task->setNote($description);
if ($deadlineSelected) {
    $task->setDeadline($deadline);
} else {
    $task->setDeadline(null);
}
$task->setCompleted($completed);
$task->setContainingObjectId($parentProject);
$task->setMilestoneId($milestone);
$task->setPriority($priority);
$task->update();
$task->publish();


Response::redirect(Request::getString('return'));
?>