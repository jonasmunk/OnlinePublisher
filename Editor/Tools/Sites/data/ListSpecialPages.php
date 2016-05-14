<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$list = SpecialPage::search();

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Type'));
$writer->header(array('title'=>'Side'));
$writer->header(array('title'=>'Sprog'));
$writer->endHeaders();

foreach ($list as $object) {
	$page = Page::load($object->getPageId());
	$writer->startRow(array( 'kind'=>'specialpage', 'id'=>$object->getId()));
	$writer->startCell()->text($object->getType())->endCell();
	$writer->startCell()->text($page ? $page->getTitle() : '!! findes ikke !!')->endCell();
	$writer->startCell()->text($object->getLanguage())->endCell();
	$writer->endRow();
}
$writer->endList();
?>