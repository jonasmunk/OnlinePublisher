<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Objects/Problem.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');

$problem = Problem::load($id);
$parentProject = $problem->getContainingObjectId();
$problem->remove();


Response::redirect(Request::getString('return'));
?>