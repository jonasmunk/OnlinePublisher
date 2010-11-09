<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'ImagesController.php';

$groupId = requestGetNumber('groupId');
$imageId = requestGetNumber('imageId');

$sql="delete from imagegroup_image where image_id=".$imageId." and imagegroup_id=".ImagesController::getGroupId();
Database::delete($sql);

$sql="delete from imagegroup_image where image_id=".$imageId." and imagegroup_id=".$groupId;
Database::delete($sql);

$sql="insert into imagegroup_image (image_id, imagegroup_id) values (".$imageId.",".$groupId.")";
Database::insert($sql);

ImagesController::setUpdateHierarchy(true);

redirect(ImagesController::getBaseWindow());
?>