<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($page = Page::load($data->id)) {
	$page->setLanguage($data->language);
	$page->save();
	PageService::markChanged($page->getId());	
}
?>