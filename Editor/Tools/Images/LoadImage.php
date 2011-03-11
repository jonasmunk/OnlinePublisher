<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Objects/Image.php';

$id = Request::getInt('id');
$image=Image::load($id);
$image->toUnicode();

$groups = $image->getGroupIds();

In2iGui::sendObject(array('image' => $image, 'groups' => $groups));
?>