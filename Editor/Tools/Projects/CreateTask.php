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
require_once '../../Classes/Task.php';

$title = requestPostText('title');
$description = requestPostText('description');
$parentProject = requestPostNumber('parentProject');
$milestone = requestPostNumber('milestone');
$priority = requestPostFloat('priority');
$deadlineSelected = requestPostCheckbox('deadlineSelected');
$deadline = requestPostDateTime('deadline');

$return = requestPostText('return');

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

redirect($return);
?>