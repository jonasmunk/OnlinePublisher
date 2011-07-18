<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';
require_once '../../../Classes/Modules/Warnings/WarningService.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Problem','width'=>40));
$writer->header(array('title'=>'Objekt'));
$writer->header(array('width'=>1));
$writer->endHeaders();

$warnings = WarningService::getWarnings();

$icons = array('warning'=>'common/warning','ok'=>'common/success');

foreach ($warnings as $warning) {
	$entity = $warning->getEntity();
	$writer->startRow();
	$writer->startCell(array('icon'=>$icons[$warning->getStatus()]))->text($warning->getText())->endCell();
	if ($entity) {
		$writer->startCell(array('icon'=>$entity['icon']))->text($entity['title'])->endCell();
	} else {
		$writer->startCell()->endCell();
	}
	$writer->startCell()->button(array('text'=>'Fiks','data'=>array('type'=>'pages')))->endCell();
	$writer->endRow();
}

$writer->endList();
?>