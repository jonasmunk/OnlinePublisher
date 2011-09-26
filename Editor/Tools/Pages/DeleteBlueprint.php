<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Pageblueprint.php';

$id = Request::getInt('id');

$blueprint = Pageblueprint::load($id);
$blueprint->remove();

Response::redirect('Blueprints.php');
?>