<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/PublishingService.php';

PublishingService::publishAll();
?>