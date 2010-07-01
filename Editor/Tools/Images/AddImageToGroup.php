<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Imagegroup.php';
require_once 'ImagesController.php';

$groupId = Request::getInt('groupId');
$imageId = Request::getInt('imageId');

$group = ImageGroup::load($groupId);
$group->addImage($imageId);

ImagesController::setUpdateHierarchy(true);

redirect(ImagesController::getBaseWindow());
?>