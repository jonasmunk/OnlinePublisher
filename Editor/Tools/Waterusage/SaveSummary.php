<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';
require_once '../../Classes/Modules/Water/WatermeterSummary.php';

$data = Request::getObject('data');

$summary = new WatermeterSummary();
$summary->setWatermeterId($data->watermeterId);
$summary->setNumber(StringUtils::fromUnicode($data->number));
$summary->setStreet(StringUtils::fromUnicode($data->street));
$summary->setCity(StringUtils::fromUnicode($data->city));
$summary->setZipcode(StringUtils::fromUnicode($data->zipcode));

WaterusageService::saveSummary($summary);
?>