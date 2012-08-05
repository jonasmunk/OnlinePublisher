<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$news = News::load($id);
if ($news) {
	$news->remove();
}
Response::sendObject(array('success'=>true));
?>