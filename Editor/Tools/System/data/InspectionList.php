<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';
require_once '../../../Classes/Modules/Inspection/InspectionService.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Problem','width'=>40));
$writer->header(array('title'=>'Objekt'));
$writer->header(array('title'=>'Kategori'));
$writer->header(array('width'=>1));
$writer->endHeaders();

$inspections = InspectionService::performInspection(array(
	'status' => Request::getString('status'),
	'category' => Request::getString('category')
));

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
	$writer->startCell()->text($inspection->getCategory())->endCell();
	$writer->startCell()->button(array('text'=>'Fiks','data'=>array('type'=>'pages')))->endCell();
	$writer->endRow();
}

$writer->endList();
?>