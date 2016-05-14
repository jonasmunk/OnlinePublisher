<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($hierarchy = Hierarchy::load($id)) {
	if ($hierarchy->delete()) {
		exit; // Success
	}
}
Response::badRequest();
?>