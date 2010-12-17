<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/Newssource.php';
require_once '../../Classes/In2iGui.php';

$data = Request::getObject('data');
if ($data->id) {
	$news = Newssource::load($data->id);
} else {
	$news = new Newssource();
}
if ($news) {
	$links = In2iGui::fromLinks($data->links);
	$news->setTitle(Request::fromUnicode($data->title));
	$news->setUrl(Request::fromUnicode($data->url));
	$news->save();
	$news->publish();
}
?>