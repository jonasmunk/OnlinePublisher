<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');

$hier = Hierarchy::load($id);
$hier->publish();

Response::redirect('EditHierarchy.php?id='.$id);
?>