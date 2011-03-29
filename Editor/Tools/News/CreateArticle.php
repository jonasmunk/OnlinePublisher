<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../Include/Private.php';


$data = Request::getObject('data');

$article = new NewsArticle();
$article->setPageBlueprintId($data->blueprint);
$article->setTitle($data->title);
NewsService::createArticle($article);
?>