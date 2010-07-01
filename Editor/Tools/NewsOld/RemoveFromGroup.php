<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'NewsController.php';

$id = NewsController::getGroupId();
$news = requestPostArray('news');

for ($i=0;$i<count($news);$i++) {
	$sql="delete from newsgroup_news where news_id=".$news[$i].
	" and newsgroup_id=".$id;
	Database::delete($sql);
}

NewsController::setUpdateHierarchy(true);
redirect('NewsList.php');
?>