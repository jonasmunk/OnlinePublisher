<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id) {
	if (!$item = Hierarchy::load($data->id)) {
		Response::badRequest();
		exit;
	}
} else {
	$item = new Hierarchy();
}
$item->setName($data->name);
$item->setLanguage($data->language);
$item->save();
?>