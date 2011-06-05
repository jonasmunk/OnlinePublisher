<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Model/HierarchyItem.php';

$id = Request::getInt('id');
$item=HierarchyItem::load($id);

In2iGui::sendUnicodeObject(array(
	'id' => $item->getId(),
	'title' => $item->getTitle(),
	'hidden' => $item->getHidden(),
	'targetType' => $item->getTargetType(),
	'targetValue' => $item->getTargetValue()
));
?>