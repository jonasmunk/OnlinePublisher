<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/News.php';
require_once '../../Classes/In2iGui.php';

$id = Request::getInt('id');
$news = News::load($id);
if ($news) {
	$news->remove();
}
In2iGui::sendObject(array('success'=>true));
?>