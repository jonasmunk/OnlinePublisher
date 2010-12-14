<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Services/PublishingService.php';
require_once '../../Classes/InternalSession.php';

$id = InternalSession::getPageId();

PublishingService::publishPage($id);

redirect('Toolbar.php');
?>