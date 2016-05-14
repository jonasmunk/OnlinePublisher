<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$obj = SpecialPage::load($id);
if ($obj) {
	Response::sendObject($obj);
} else {
	Response::notFound();
}
?>