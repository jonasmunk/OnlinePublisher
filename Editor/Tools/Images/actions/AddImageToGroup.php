<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

ImageService::addImageToGroup($data->image,$data->group);
?>