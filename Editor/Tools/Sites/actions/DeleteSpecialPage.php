<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
if ($obj = SpecialPage::load($id)) {
	$obj->remove();
} else {
	Response::badRequest();
}
?>