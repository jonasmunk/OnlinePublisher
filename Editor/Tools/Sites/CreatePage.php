<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Hierarchy.php';

$design = Request::getInt('design');
$frame = Request::getInt('frame');
$template = Request::getInt('template');
$title = Request::getUnicodeString('title');
$menuItem = Request::getUnicodeString('menuItem');
$description = Request::getUnicodeString('description');
$path = Request::getUnicodeString('path');
$language = Request::getUnicodeString('language');
$menuItemKind = Request::getUnicodeString('menuItemKind');
$menuItemId = Request::getInt('menuItemId');

if ($menuItem=='') {
	$menuItem = $title;
}

$page = new Page();

$page->setTemplateId($template);
$page->setDesignId($design);
$page->setFrameId($frame);
$page->setTitle($title);
$page->setDescription($description);
$page->setPath($path);
$page->setLanguage($language);
$page->create();

if ($menuItemKind=='hierarchy') {
	$hierarchy = Hierarchy::load($menuItemId);
	$hierarchy->createItemForPage($page->getId(),$menuItem,0);
	$hierarchy->markChanged();
} else if ($menuItemKind=='hierarchyItem') {
	$hierarchy = Hierarchy::loadFromItemId($menuItemId);
	$hierarchy->createItemForPage($page->getId(),$menuItem,$menuItemId);
	$hierarchy->markChanged();
}
?>