<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Services/ImageService.php';
require_once '../../Classes/Interface/In2iGui.php';

$url = Request::getString('url');

$response = ImageService::createImageFromUrl($url);

In2iGui::sendObject($response);
?>