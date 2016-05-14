<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$result = Hierarchy::deleteItem($id);

if ($result===null) {
	Response::badRequest();
}
?>