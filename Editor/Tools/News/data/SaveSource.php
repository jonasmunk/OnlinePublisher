<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Objects/Newssource.php';

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