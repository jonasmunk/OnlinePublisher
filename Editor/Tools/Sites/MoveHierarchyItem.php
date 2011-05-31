<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);
$direction = Request::getString('direction');

Hierarchy::moveItem($id,$direction=='down' ? 1 : -1);
?>