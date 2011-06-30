<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$value = Request::getObject('data');

$settings = OptimizationService::setSettings($value);

?>