<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$settings = OptimizationService::getSettings();

Response::sendObject($settings);
?>