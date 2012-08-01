<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$image=Image::load($id);
$image->toUnicode();

$groups = $image->getGroupIds();

In2iGui::sendObject(array('image' => $image, 'groups' => $groups));
?>