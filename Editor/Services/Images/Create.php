<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Support
 */
require_once '../../Include/Private.php';

$data = Request::getString('data');
$title = Request::getString('title');

$result = ImageService::createImageFromBase64($data,null,$title);
if ($result['success']) {
	$image = $result['image'];
	Response::sendObject(array('id'=>$image->getId()));
} else {
	Response::badRequest();
}	
?>