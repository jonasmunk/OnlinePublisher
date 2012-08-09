<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$url = Request::getString('url');

$response = FileService::createFromUrl($url);

Response::sendUnicodeObject($response);
?>