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

$data = Request::getUnicodeObject('data');

$page = Page::load($data->id);

$page->setTitle($data->title);
$page->setDescription($data->description);
$page->setPath($data->path);
$page->setSearchable($data->searchable);
$page->setDisabled($data->disabled);
$page->setLanguage($data->language);
$page->save();

Hierarchy::markHierarchyOfPageChanged($page->getId());
?>