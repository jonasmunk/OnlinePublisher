<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');
if ($data->id) {
	$news = News::load($data->id);
} else {
	$news = new News();
}
if ($news) {
	$links = In2iGui::fromLinks($data->links);
	$news->setTitle($data->title);
	$news->setNote($data->note);
	$news->setStartdate($data->startdate);
	$news->setEnddate($data->enddate);
	$news->save();
	$news->updateLinks($links);
	$news->updateGroupIds($data->groups);
	$news->publish();
}
?>