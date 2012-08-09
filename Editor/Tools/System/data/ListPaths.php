<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>array('Path','da'=>'Sti'),'width'=>40));
$writer->header(array('title'=>array('Page','da'=>'Side'),'width'=>30));
$writer->header(array('title'=>array('Page path','da'=>'Side-sti'),'width'=>30));
$writer->endHeaders();

$list = Query::after('path')->get();
foreach ($list as $item) {
	$page = Page::load($item->getPageId());
	$writer->startRow(array('kind'=>'path','id'=>$item->getId()));
	$writer->startCell(array('icon'=>$item->getIn2iGuiIcon()))->text($item->getPath())->endCell();
	if ($page) {
		$writer->startCell(array('icon'=>$page->getIn2iGuiIcon()))->text($page->getTitle())->endCell();
	} else {
		$writer->startCell(array('icon'=>'common/warning'))->text(array('No page','da'=>'Ingen siden'))->endCell();
	}
	$writer->startCell()->text($page ? $page->getPath() : '')->endCell();
	$writer->endRow();
}
$writer->endList();
?>