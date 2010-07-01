<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Problem.php';

$id = requestPostNumber('id');
$title = requestPostText('title');
$description = requestPostText('description');
$parentProject = requestPostNumber('parentProject');
$milestone = requestPostNumber('milestone');
$priority = requestPostFloat('priority');
$deadlineSelected = requestPostCheckbox('deadlineSelected');
$completed = requestPostCheckbox('completed');
$deadline = requestPostDateTime('deadline');

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

redirect(requestPostText('return'));
?>