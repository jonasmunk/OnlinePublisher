<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

Log::debug($data);

if ($data->id) {
	$obj = SpecialPage::load($data->id);
	if (!$obj) {
		Response::badRequest();
		exit;
	}
} else {
	$obj = new SpecialPage();
}
$obj->setLanguage($data->language);
$obj->setType($data->type);
$obj->setPageId($data->pageId);

$obj->save();

Log::debug($obj);
?>