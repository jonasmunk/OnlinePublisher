<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getInt('id');
$title = Request::getString('title');
$description = Request::getString('description');
$keywords = Request::getString('keywords');
$path = Request::getString('path');
$language = Request::getString('language');

$page = Page::load($id);
$page->setTitle($title);
$page->setDescription($description);
$page->setKeywords($keywords);
$page->setPath($path);
$page->setLanguage($language);
$page->save();

?>