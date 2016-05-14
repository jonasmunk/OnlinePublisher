<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

$article = new NewsArticle();
$article->setPageBlueprintId($data->blueprint);
$article->setLinkText($data->linkText);
$article->setTitle($data->title);
$article->setText($data->text);
$article->setSummary($data->summary);
$article->setStartDate($data->startdate);
$article->setEndDate($data->enddate);
$article->setGroupIds($data->groups);

NewsService::createArticle($article);
?>