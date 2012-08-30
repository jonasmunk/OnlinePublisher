<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$inspections = InspectionService::performInspection(array('status'=>Request::getString('status')));

$writer = new ListWriter();

$writer->startList(array('unicode'=>true));
$icons = array('warning'=>'common/warning','ok'=>'common/success','error'=>'common/stop');

foreach ($inspections as $inspection) {
	$entity = $inspection->getEntity();
	$writer->startRow();
	$writer->startCell(array('icon'=>$icons[$inspection->getStatus()]))->text($inspection->getText())->endCell();
	if ($entity) {
		$writer->startCell(array('icon'=>$entity['icon']))->text($entity['title'])->endCell();
	} else {
		$writer->startCell()->endCell();
	}
	$writer->endRow();
}
$writer->endList();
?>