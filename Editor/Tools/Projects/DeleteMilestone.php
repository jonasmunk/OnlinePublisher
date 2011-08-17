<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Objects/Milestone.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');

$milestone = Milestone::load($id);
$milestone->remove();

Response::redirect('Milestones.php');
?>