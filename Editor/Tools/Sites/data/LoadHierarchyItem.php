<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$item = HierarchyItem::load($id);

Response::sendObject(array(
	'id' => $item->getId(),
	'title' => $item->getTitle(),
	'hidden' => $item->getHidden(),
	'targetType' => $item->getTargetType(),
	'targetValue' => $item->getTargetValue(),
	'canDelete' => $item->getCanDelete()
));
?>