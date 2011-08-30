<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Sti','width'=>40));
$writer->header(array('title'=>'Side','width'=>30));
$writer->header(array('title'=>'Side-sti','width'=>30));
$writer->endHeaders();

$list = Object::find(array('type'=>'path'));
foreach ($list['result'] as $item) {
	$page = Page::load($item->getPageId());
	$writer->startRow(array('kind'=>'path','id'=>$item->getId()));
	$writer->startCell(array('icon'=>$item->getIn2iGuiIcon()))->text($item->getPath())->endCell();
	if ($page) {
		$writer->startCell(array('icon'=>$page->getIn2iGuiIcon()))->text($page->getTitle())->endCell();
	} else {
		$writer->startCell(array('icon'=>'monochrome/warning'))->text('!!! ingen !!!')->endCell();
	}
	$writer->startCell()->text($page ? $page->getPath() : '')->endCell();
	$writer->endRow();
}
$writer->endList();
?>