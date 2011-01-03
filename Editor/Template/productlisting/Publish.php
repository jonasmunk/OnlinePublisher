<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ProductListing
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Services/PublishingService.php';
require_once '../../Classes/InternalSession.php';

$id = InternalSession::getPageId();

PublishingService::publishPage($id);

Response::redirect('Toolbar.php');
?>