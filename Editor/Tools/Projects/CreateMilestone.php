<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Milestone.php';
require_once '../../Classes/Request.php';

$title = Request::getString('title');
$description = Request::getString('description');
$project = Request::getInt('project');
$deadlineSelected = Request::getCheckbox('deadlineSelected');
$deadline = Request::getDateTime('deadline');

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