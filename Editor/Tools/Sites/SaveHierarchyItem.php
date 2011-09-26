<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Model/HierarchyItem.php';

$data = Request::getUnicodeObject('data');

if ($data->id) {
	$item = HierarchyItem::load($data->id);
	$item->setTitle($data->title);
	$item->setHidden($data->hidden);
	if ($data->targetType) {
		$item->setTargetType($data->targetType);
		$item->setTargetValue($data->targetValue);
	}
	$item->save();
} else if ($data->parent) {
	if ($data->parent->kind=='hierarchy') {
		$hierarchy = Hierarchy::load($data->parent->id);
		$parent = 0;
	} else if ($data->parent->kind=='hierarchyItem') {
		$hierarchy = Hierarchy::loadFromItemId($data->parent->id);
		$parent = $data->parent->id;
	} else {
		Response::badRequest();
		exit;
	}
	$result = $hierarchy->createItem(array(
		'title' => $data->title,
		'hidden' => $data->hidden,
		'targetType' => $data->targetType,
		'targetValue' => $data->targetValue,
		'parent' => $parent
	));
	if ($result===false) {
		Response::badRequest();
	}
} else {
	Response::internalServerError();
}
?>