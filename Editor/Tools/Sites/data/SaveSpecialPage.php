<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Model/SpecialPage.php';

$data = Request::getUnicodeObject('data');

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