<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';
$id = Request::getInt('id');
$data = Request::getUnicodeObject('frame');
$topLinks = Request::getUnicodeObject('topLinks');
$bottomLinks = Request::getUnicodeObject('bottomLinks');
$search = Request::getUnicodeObject('search');
$user = Request::getUnicodeObject('user');

if ($id>0) {
	$object = Frame::load($id);
} else {
	$object = new Frame();
}
if ($object) {
	$object->setTitle($data->title);
	$object->setName($data->name);
	$object->setBottomText($data->bottomText);
	$object->setHierarchyId($data->hierarchyId);
	$object->setSearchEnabled($search->enabled);
	$object->setSearchPageId($search->pageId);
	$object->setUserStatusEnabled($user->enabled);
	$object->setLoginPageId($user->pageId);
	$object->save();

	FrameService::replaceLinks($object,$topLinks,$bottomLinks);
}
?>