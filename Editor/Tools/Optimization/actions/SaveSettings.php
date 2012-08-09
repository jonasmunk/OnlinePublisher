<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$value = Request::getObject('data');
//Log::debug(StringUtils::toJSON(StringUtils::toUnicode($value)));

$settings = OptimizationService::setSettings($value);

?>