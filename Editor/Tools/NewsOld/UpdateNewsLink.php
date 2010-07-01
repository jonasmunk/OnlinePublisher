<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/News.php';

$id = requestPostNumber('id');
$newsId = requestPostNumber('news');

$title = requestPostText('title');
$alternative = requestPostText('alternative');

$targetValue='';
$targetType=requestPostText('type');
if ($targetType=='url') {
	$targetValue = requestPostText('url');
}
else if ($targetType=='page') {
	$targetValue = requestPostText('page');
}
else if ($targetType=='file') {
	$targetValue = requestPostText('file');
}
else if ($targetType=='email') {
	$targetValue = requestPostText('email');
}

$news = News::load($newsId);
$news->updateLink($id,$title,$alternative,'',$targetType,$targetValue);


redirect('NewsLinks.php?id='.$newsId);
?>