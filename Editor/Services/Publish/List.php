<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Object.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Services/PublishingService.php';

$pages = PublishingService::getUnpublishedPages();
$hierarchies = PublishingService::getUnpublishedHierarchies();
$objects = PublishingService::getUnpublishedObjects();

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Titel','width'=>70));
$writer->header(array('title'=>'Type','width'=>30));
$writer->header();
$writer->endHeaders();

foreach ($pages as $page) {
	$writer->startRow(array('kind'=>'page','id'=>$page['id']));
	$writer->startCell(array('icon'=>'common/page'))->text($page['title'])->endCell();
	$writer->startCell()->text('Side')->endCell();
	$writer->startCell(array('wrap'=>false))->button(array('text'=>'Udgiv'))->endCell();
	$writer->endRow();
}

foreach ($hierarchies as $hierarchy) {
	$writer->startRow(array('kind'=>'hierarchy','id'=>$hierarchy['id']));
	$writer->startCell(array('icon'=>'common/hierarchy'))->text($hierarchy['name'])->endCell();
	$writer->startCell()->text('Hierarki')->endCell();
	$writer->startCell(array('wrap'=>false))->button(array('text'=>'Udgiv'))->endCell();
	$writer->endRow();	
}

foreach ($objects as $object) {
	$writer->startRow(array('kind'=>'object','id'=>$object->getId()));
	$writer->startCell(array('icon'=>$object->getIn2iGuiIcon()))->text($object->getTitle())->endCell();
	$writer->startCell()->text($object->getType())->endCell();
	$writer->startCell(array('wrap'=>false))->button(array('text'=>'Udgiv'))->endCell();
	$writer->endRow();
}

$writer->endList();
?>