<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Design.php';
require_once '../../Classes/In2iGui.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Design','width'=>40));
$writer->header(array('title'=>'Nøgle','width'=>30));
$writer->endHeaders();

$designs = Design::search();
foreach ($designs as $item) {
	$writer->startRow(array('kind'=>'design','id'=>$item->getId()));
	$writer->startCell(array('icon'=>$item->getIn2iGuiIcon()))->text($item->getTitle())->endCell();
	$writer->startCell()->text($item->getUnique())->endCell();
	$writer->endRow();
}
$writer->endList();
?>