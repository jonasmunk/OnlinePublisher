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
require_once '../../Classes/Request.php';

$id = Request::getInt('id');

$problem = Problem::load($id);
$parentProject = $problem->getContainingObjectId();
$problem->remove();


redirect(Request::getString('return'));
?>