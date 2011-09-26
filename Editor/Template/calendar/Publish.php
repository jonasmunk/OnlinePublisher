<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Services/PublishingService.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Response.php';

$id = InternalSession::getPageId();

PublishingService::publishPage($id);

Response::redirect('Toolbar.php');
?>