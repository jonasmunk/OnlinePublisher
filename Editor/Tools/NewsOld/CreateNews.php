<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/News.php';
require_once 'NewsController.php';

$title = requestPostText('title');
$description = requestPostText('description');
$searchable = requestPostCheckbox('searchable');
$image = requestPostNumber('image');
$groups = requestPostArray('groups');
if (requestPostCheckbox('startdateCheck')) {
	$startdate = requestPostDateTime('startdate');
}
else {
	$startdate = NULL;
}
if (requestPostCheckbox('enddateCheck')) {
	$enddate = requestPostDateTime('enddate');
}
else {
	$enddate = NULL;
}

$news = new News();
$news->setTitle($title);
$news->setNote($description);
$news->setStartdate($startdate);
$news->setEnddate($enddate);
$news->setSearchable($searchable);
$news->setImageId($image);
$news->create();
$newsId = $news->getId();

$news->updateGroupIds($groups);


$linkTitle = requestPostText('linkTitle');
$linkAlternative = requestPostText('linkAlternative');
$linkTargetType=requestPostText('linkType');
$linkTargetValue='';
if ($linkTitle!='') {
	if ($linkTargetType=='url') {
		$linkTargetValue = requestPostText('url');
	}
	else if ($linkTargetType=='page') {
		$linkTargetValue = requestPostText('page');
	}
	else if ($linkTargetType=='file') {
		$linkTargetValue = requestPostText('file');
	}
	else if ($linkTargetType=='email') {
		$linkTargetValue = requestPostText('email');
	}

	$news->addLink($linkTitle,$linkAlternative,'',$linkTargetType,$linkTargetValue);
}

NewsController::setUpdateHierarchy(true);
redirect('NewsProperties.php?id='.$newsId);
?>