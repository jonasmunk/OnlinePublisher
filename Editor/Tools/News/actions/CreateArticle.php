<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

$article = new NewsArticle();
$article->setPageBlueprintId($data->blueprint);
$article->setLinkText(StringUtils::fromUnicode($data->linkText));
$article->setTitle(StringUtils::fromUnicode($data->title));
$article->setText(StringUtils::fromUnicode($data->text));
$article->setSummary(StringUtils::fromUnicode($data->summary));
$article->setStartDate($data->startdate);
$article->setEndDate($data->enddate);
$article->setGroupIds($data->groups);

Log::debug($article);

NewsService::createArticle($article);
?>