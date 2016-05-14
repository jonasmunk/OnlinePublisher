<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

$settings = OptimizationService::getSettings();

$settings->purpose = $data->purpose;
$settings->successcriteria = $data->successcriteria;
$settings->audiences = $data->audiences;

OptimizationService::setSettings($settings);
?>