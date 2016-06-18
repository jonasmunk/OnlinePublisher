<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$imageId = Request::getInt('image');
$groupId = Request::getInt('group');
$direction = Request::getString('direction');

ImageService::moveImageInGroup($groupId, $imageId, $direction == 'up');
?>