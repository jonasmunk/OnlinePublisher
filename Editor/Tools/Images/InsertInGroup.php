<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Imagegroup.php';
require_once 'ImagesController.php';

$id = requestPostNumber('id',0);
$images = requestPostArray('image');

$group = ImageGroup::load($id);
$group->addImages($images);

ImagesController::setUpdateHierarchy(true);

redirect('Group.php');
?>