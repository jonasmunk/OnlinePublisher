<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$file = News::load($id);
if ($file) {
	$groups = $file->getGroupIds();

	$links = In2iGui::toLinks($file->getLinks());

	Response::sendObject(array('news' => $file, 'groups' => $groups, 'links' => $links));
} else {
	Response::notFound();
}
?>