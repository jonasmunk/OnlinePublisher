<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../Include/Private.php';


$data = Request::getObject('data');

$article = new NewsArticle();
$article->setPageBlueprintId($data->blueprint);
$article->setLinkText($data->linkText);
$article->setTitle($data->title);
$article->setText($data->text);
$article->setSummary($data->summary);
NewsService::createArticle($article);
?>