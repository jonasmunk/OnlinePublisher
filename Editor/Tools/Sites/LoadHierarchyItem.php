<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/HierarchyItem.php';

$id = Request::getInt('id');
$item=HierarchyItem::load($id);
$item->toUnicode();

In2iGui::sendObject($item);
?>