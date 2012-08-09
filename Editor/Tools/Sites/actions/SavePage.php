<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($page = Page::load($data->id)) {
	$page->setTitle($data->title);
	$page->setDescription($data->description);
	$page->setPath($data->path);
	$page->setSearchable($data->searchable);
	$page->setDisabled($data->disabled);
	$page->setLanguage($data->language);
	$page->setDesignId($data->designId);
	$page->setFrameId($data->frameId);
	$page->save();
}
?>