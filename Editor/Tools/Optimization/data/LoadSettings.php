<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$settings = OptimizationService::getSettings();

Response::sendObject($settings);
?>