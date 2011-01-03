<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once 'ProjectsController.php';

ProjectsController::setGroupingOpen(Request::getString('type'),Request::getBoolean('open'))
?>