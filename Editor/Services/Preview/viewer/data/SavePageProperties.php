<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../../Config/Setup.php';
require_once '../../../../Include/Security.php';
require_once '../../../../Classes/Request.php';
require_once '../../../../Classes/In2iGui.php';
require_once '../../../../Classes/Page.php';

$id = Request::getInt('id');
$title = Request::getUnicodeString('title');
$description = Request::getUnicodeString('description');
$keywords = Request::getUnicodeString('keywords');
$path = Request::getUnicodeString('path');
$language = Request::getUnicodeString('language');

$page = Page::load($id);
$page->setTitle($title);
$page->setDescription($description);
$page->setKeywords($keywords);
$page->setPath($path);
$page->setLanguage($language);
$page->save();

In2iGui::respondSuccess();
?>