<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Task.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');

$task = Task::load($id);
$parentProject = $task->getContainingObjectId();
$task->remove();

Response::redirect(Request::getString('return'));
?>