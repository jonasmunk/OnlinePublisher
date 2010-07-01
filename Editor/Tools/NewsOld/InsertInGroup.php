<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'NewsController.php';

$id = requestPostNumber('id',0);
$news = requestPostArray('news');


for ($i=0;$i<count($news);$i++) {
	$sql="insert into newsgroup_news (news_id, newsgroup_id)".
	" values (".$news[$i].",".$id.")";
	Database::insert($sql);
}

NewsController::setUpdateHierarchy(true);

redirect('Group.php');
?>