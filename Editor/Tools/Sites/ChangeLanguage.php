<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Model/Page.php';

$data = Request::getObject('data');

if ($page = Page::load($data->id)) {
	$page->setLanguage($data->language);
	$page->save();
	PageService::markChanged($page->getId());	
}
?>