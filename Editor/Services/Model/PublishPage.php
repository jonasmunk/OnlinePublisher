<?php
/**
 * @package OnlinePublisher
 * @subpackage Services/Model
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

PublishingService::publishPage(Request::getId());
?>