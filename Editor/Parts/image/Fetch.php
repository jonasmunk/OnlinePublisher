<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Log.php';
require_once '../../Classes/Services/ImageService.php';

$url = Request::getString('url');

$response = ImageService::createImageFromUrl($url);
In2iGui::sendObject($response);
?>