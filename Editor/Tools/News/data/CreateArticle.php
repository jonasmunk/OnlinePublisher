<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

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