<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/HierarchyItem.php';

$data = Request::getObject('data');

$item = HierarchyItem::load($data->id);

$item->setTitle(Request::fromUnicode($data->title));
$item->setHidden($data->hidden);
$item->save();
?>