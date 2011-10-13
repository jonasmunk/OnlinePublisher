<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Model/Page.php';
require_once '../../Classes/Model/Hierarchy.php';

$data = Request::getUnicodeObject('data');

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