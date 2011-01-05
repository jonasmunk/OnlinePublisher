<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Milestone.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');
$title = Request::getString('title');
$description = Request::getString('description');
$project = Request::getInt('project');
$deadlineSelected = Request::getCheckbox('deadlineSelected');
$completed = Request::getCheckbox('completed');
$deadline = Request::getDateTime('deadline');

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

Response::redirect('Milestones.php');
?>