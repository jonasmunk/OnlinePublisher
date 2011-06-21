<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Support
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

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