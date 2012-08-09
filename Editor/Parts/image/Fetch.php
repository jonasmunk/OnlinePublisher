<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../Include/Private.php';

$url = Request::getString('url');

$response = ImageService::createImageFromUrl($url);
Response::sendUnicodeObject($response);
?>