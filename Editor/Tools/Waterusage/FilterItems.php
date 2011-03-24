<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';
require_once '../../Classes/In2iGui.php';

$years = WaterusageService::getYears();

$writer = new ItemsWriter();

$writer->startItems();
foreach ($years as $year) {
	$writer->item(array(
		'value'=>$year,
		'title'=>$year,
		'icon'=>'common/time',
		'kind'=>'year'
	));
}
$writer->endItems();
?>