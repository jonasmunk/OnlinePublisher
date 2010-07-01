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

$id = requestGetNumber('id');

$task = Task::load($id);
$parentProject = $task->getContainingObjectId();
$task->remove();


redirect(requestGetText('return'));
?>