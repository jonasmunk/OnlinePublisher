<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$value = Request::getObject('data');

$settings = OptimizationService::setSettings($value);

?>