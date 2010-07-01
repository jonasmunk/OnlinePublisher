<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Page.php';

$data = Request::getObject('data');

$page = Page::load($data->id);

$page->setTitle(Request::fromUnicode($data->title));
$page->setDescription(Request::fromUnicode($data->description));
$page->setPath(Request::fromUnicode($data->path));
$page->setSearchable($data->searchable);
$page->setDisabled($data->disabled);
$page->setLanguage($data->language);
$page->save();
?>