<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Objects/Task.php';
require_once '../../Classes/Core/Request.php';

$title = Request::getString('title');
$description = Request::getString('description');
$parentProject = Request::getInt('parentProject');
$milestone = Request::getInt('milestone');
$priority = Request::getFloat('priority');
$deadlineSelected = Request::getCheckbox('deadlineSelected');
$deadline = Request::getDateTime('deadline');

$return = Request::getString('return');

$task = new Task();
$task->setTitle($title);
$task->setNote($description);
if ($deadlineSelected) {
    $task->setDeadline($deadline);
}
$task->setContainingObjectId($parentProject);
$task->setMilestoneId($milestone);
$task->setPriority($priority);
$task->create();
$task->publish();

Response::redirect($return);
?>