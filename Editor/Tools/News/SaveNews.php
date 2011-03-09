<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Objects/News.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Log.php';

$data = Request::getObject('data');
if ($data->id) {
	$news = News::load($data->id);
} else {
	$news = new News();
}
if ($news) {
	$links = In2iGui::fromLinks($data->links);
	$news->setTitle(Request::fromUnicode($data->title));
	$news->setNote(Request::fromUnicode($data->note));
	$news->setStartdate($data->startdate);
	$news->setEnddate($data->enddate);
	$news->save();
	$news->updateLinks($links);
	$news->updateGroupIds($data->groups);
	$news->publish();
}
?>