<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Imagegroup.php';
require_once 'ImagesController.php';

$id = ImagesController::getGroupId();
$images = requestPostArray('image');
$single = requestGetNumber('id',0);
if ($single>0) $images=array($single);

$group=ImageGroup::load($id);
$group->removeImages($images);

ImagesController::setUpdateHierarchy(true);
if ($single>0) {
	redirect('Group.php');
}
else {
	redirect('ListView.php');
}
?>