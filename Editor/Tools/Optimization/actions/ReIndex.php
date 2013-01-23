<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

PublishingService::reIndexPage($id);
?>