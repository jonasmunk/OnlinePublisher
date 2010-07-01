<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Milestone.php';

$id = requestPostNumber('id');
$title = requestPostText('title');
$description = requestPostText('description');
$project = requestPostNumber('project');
$deadlineSelected = requestPostCheckbox('deadlineSelected');
$completed = requestPostCheckbox('completed');
$deadline = requestPostDateTime('deadline');

$milestone = Milestone::load($id);
$milestone->setTitle($title);
$milestone->setNote($description);
if ($deadlineSelected) {
    $milestone->setDeadline($deadline);
} else {
    $milestone->setDeadline(null);
}
$milestone->setCompleted($completed);
$milestone->setContainingObjectId($project);
$milestone->update();
$milestone->publish();


redirect('Milestones.php');
?>