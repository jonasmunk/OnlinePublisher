<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

$summary = new WatermeterSummary();
$summary->setWatermeterId($data->watermeterId);
$summary->setNumber($data->number);
$summary->setStreet($data->street);
$summary->setCity($data->city);
$summary->setZipcode($data->zipcode);
$summary->setPhone($data->phone);
$summary->setEmail($data->email);

WaterusageService::saveSummary($summary);
?>