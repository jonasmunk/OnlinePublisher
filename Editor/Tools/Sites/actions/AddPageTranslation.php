<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$page = Request::getInt('page');
$translation = Request::getInt('translation');

PageService::addPageTranslation($page,$translation);
?>