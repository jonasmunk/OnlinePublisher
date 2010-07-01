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
require_once '../../Classes/Milestone.php';

$title = requestPostText('title');
$description = requestPostText('description');
$project = requestPostNumber('project');
$deadlineSelected = requestPostCheckbox('deadlineSelected');
$deadline = requestPostDateTime('deadline');

$milestone = new Milestone();
$milestone->setTitle($title);
$milestone->setNote($description);
if ($deadlineSelected) {
    $milestone->setDeadline($deadline);
}
$milestone->setContainingObjectId($project);
$milestone->create();
$milestone->publish();

redirect('Milestones.php');
?>