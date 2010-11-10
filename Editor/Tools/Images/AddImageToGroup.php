<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Image.php';

$data = Request::getObject('data');

if ($image = Image::load($data->image)) {
	$image->addGroupId($data->group);
} else {
	Log::debug('No image by id='.$data->image);
}
?>