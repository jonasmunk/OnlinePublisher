<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id',0);
$direction = Request::getString('direction');

Hierarchy::moveItem($id,$direction=='down' ? 1 : -1);
?>