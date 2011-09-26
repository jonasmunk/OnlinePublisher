<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Objects/News.php';
require_once '../../../Classes/Interface/In2iGui.php';

$id = Request::getInt('id');
$news = News::load($id);
if ($news) {
	$news->remove();
}
In2iGui::sendObject(array('success'=>true));
?>