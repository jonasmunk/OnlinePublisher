<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');
if ($data->id) {
	$news = Newssource::load($data->id);
} else {
	$news = new Newssource();
}
if ($news) {
	$news->setTitle($data->title);
	$news->setUrl($data->url);
	$news->setSyncInterval($data->syncInterval);
	$news->save();
	$news->publish();
}
?>