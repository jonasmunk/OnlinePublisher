<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$page=Page::load($id);
$page->setData(null);

Response::sendUnicodeObject(array(
	'page'=>$page
));
?>