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

$id = requestPostNumber('news',0);

$title = requestPostText('title');
$alternative = requestPostText('alternative');
$targetType=requestPostText('type');
$target=requestPostText('target');
$targetValue='';

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

$news = News::load($id);
$news->addLink($title,$alternative,$target,$targetType,$targetValue);

redirect('NewsLinks.php?id='.$id);
?>