<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$design = Request::getInt('design');
$frame = Request::getInt('frame');
$template = Request::getInt('template');
$title = Request::getString('title');
$menuItem = Request::getString('menuItem');
$description = Request::getString('description');
$path = Request::getString('path');
$language = Request::getString('language');
$menuItemKind = Request::getString('menuItemKind');
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
Response::sendObject(array('id'=>$page->getId()));
?>