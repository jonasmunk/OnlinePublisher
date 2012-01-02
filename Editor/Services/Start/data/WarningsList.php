<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';
require_once '../../../Classes/Modules/Inspection/InspectionService.php';

$result = InspectionService::performInspection(array());

$writer = new ListWriter();

$writer->startList(array('unicode'=>true));

foreach($result as $item) {
	$entity = $item->getEntity();
	$writer->startRow()->
		startCell(array('icon'=>'monochrome/warning'))->startLine()->text($item->getText())->endLine()->
		endCell()->
		startCell(array('icon'=>$entity['icon']))->text($entity['title'])->endCell()->
	endRow();
		
}
$writer->endList();
?>