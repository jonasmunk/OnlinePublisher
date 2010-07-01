<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Task.php';

$id = requestPostNumber('id');
$title = requestPostText('title');
$description = requestPostText('description');
$parentProject = requestPostNumber('parentProject');
$milestone = requestPostNumber('milestone');
$priority = requestPostFloat('priority');
$deadlineSelected = requestPostCheckbox('deadlineSelected');
$completed = requestPostCheckbox('completed');
$deadline = requestPostDateTime('deadline');

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


redirect(requestPostText('return'));
?>