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
require_once 'NewsController.php';

$id = requestPostNumber('id',0);
$title = requestPostText('title');
$image = requestPostNumber('image');
$description = requestPostText('description');
$startdate = NULL;
if (requestPostCheckbox('startdateCheck')) {
	$startdate = requestPostDateTime('startdate');
}
$enddate = NULL;
if (requestPostCheckbox('enddateCheck')) {
	$enddate = requestPostDateTime('enddate');
}
$searchable = requestPostCheckbox('searchable');
$groups = requestPostArray('groups');

$news = News::load($id);
$news->setTitle($title);
$news->setNote($description);
$news->setImageId($image);
$news->setStartdate($startdate);
$news->setEnddate($enddate);
$news->setSearchable($searchable);
$news->update();

$news->updateGroupIds($groups);

NewsController::setUpdateHierarchy(true);

redirect('NewsProperties.php?id='.$id);
?>