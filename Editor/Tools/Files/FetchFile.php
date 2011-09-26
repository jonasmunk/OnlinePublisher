<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Interface/In2iGui.php';

$url = Request::getString('url');

$response = FileService::createFromUrl($url);

In2iGui::sendObject($response);
?>